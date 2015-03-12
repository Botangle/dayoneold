<?php

class NewUserController extends BaseController
{

	public function __construct()
	{
		$this->beforeFilter( 'csrf', array(
			'only' => 'postLogin'
		) );

		$this->beforeFilter( 'auth', array(
			'except' => array( 'getLogin', 'postLogin', 'getForgot' )
		) );
	}

	public function getLogin()
	{
		return View::make( 'new.user.login' );
	}

	public function postLogin()
	{
		// we need to handle either old ways of passing in data
		// or our new view setup

		if (Input::has( 'data.User.username' )) {
			$username = Input::get( 'data.User.username' );
			$password = Input::get( 'data.User.password' );
		} else {
			$username = Input::get( 'username' );
			$password = Input::get( 'password' );
		}

		if (Input::has( 'remember_me' )) {
			$rememberMe = Input::get( 'remember_me' );
		} else {
			$rememberMe = false;
		}

		$user = User::where( 'username', $username )->first();
		if ($user) {
			$loggedIn = Auth::attempt( array(
				'username' => $username,
				'password' => $password,
			), $rememberMe );
		}
		if ( ! $user || ! $loggedIn) {
			if ($user) {
				Event::fire( 'user.login-attempt-failed', array( $user ) );
			}
//            if($this->RequestHandler->isXml()) {
//                return $this->sendXmlError(1, "The password you entered is incorrect");
//            } else {
//            }

			return Redirect::route( 'login' )
			               ->with( 'flash_error', trans( 'Your username or password is incorrect.' ) )
			               ->withInput();
		}

		Event::fire( 'user.login', array( Auth::user() ) );

		if (Input::get( 'timezone' ) != '' && Input::get( 'timezone' ) != $user->timezone) {
			switch ($user->timezone_update) {
				case User::TIMEZONE_UPDATE_AUTO:
					$user->timezone = Input::get( 'timezone' );
					$user->save();
					break;

				case User::TIMEZONE_UPDATE_ASK:
					return View::make( 'user.timezone-check', array(
						'browserTimezone' => Input::get( 'timezone' ),
						'user'            => $user,
					) );

				case User::TIMEZONE_UPDATE_NEVER:
					if (empty( $user->timezone )) {
						return Redirect::route( 'user.timezone' )
						               ->with( 'flash_error', trans( "Please confirm that your timezone is correct" ) );
					}
					break;

			}
		}

		return Redirect::intended( URL::route( 'user.my-account' ) )
		               ->with( 'flash_success', trans( 'Logged in successfully. Welcome back to Botangle.' ) );

		// API: handle our API info and send back info
//            if($this->RequestHandler->isXml()) {
//                $user = $this->Session->read('Auth.User');
//
//                // we'll translate a bit between what we've got and our system
//                $this->set('id', $user['id']);
//                $this->set('role', $user['Role']['alias']);
//                $this->set('firstname', $user['name']);
//                $this->set('lastname', $user['lname']);
//                $this->set('profilepic', $user['profilepic']);
//                $this->set('_rootNode', 'user');
//                $this->set('_serialize', array('id', 'role', 'firstname', 'lastname', 'profilepic'));
//            } else {
//            }
	}

	public function getMyAccount()
	{
		if (Auth::user()->isTutor() || Auth::user()->isAdmin()) {
			$mode = 'expert';
		} else {
			$mode = 'student';
		}
		return View::make( 'user.account.my-account', array(
			'user' => Auth::user(),
			'mode' => $mode,
		) );
	}

	public function postMyAccount()
	{
		$userId = Input::get( 'id' );
		if (Auth::user()->id != $userId && ! Auth::user()->isAdmin()) {
			App::abort( '404', 'You are not authorized to update this user account.' );
		}
		$user = User::findOrFail( $userId );

		// Copy the inputs to $inputs for later use if file upload occurs
		$inputs = Input::all();

		// Set the base context (the student account is a subset of the tutor)
		$user->addContext( 'student-save' );

		// If the user is a tutor, there are more fields to be validated and saved
		if ($user->isTutor() || $user->isAdmin()) {
			$user->addContext( 'tutor-save' );
		}
		// Only validate profilepic if a new file has been uploaded
		if (Input::hasFile( 'profilepic' )) {
			$user->addContext( 'profile-pic-upload' );
		}
		if (isset( $inputs['subject'] )) {
			Category::resetUserCountCaches( explode( ", ", $user->subject ), $inputs['subject'] );
			$inputs['subject'] = implode( ", ", $inputs['subject'] );
		} else {
			Category::resetUserCountCaches( explode( ", ", $user->subject ), [ ] );
		}
		$user->fill( $inputs );

		if ( ! $user->validate()) {
			if (isset( $inputs['profilepic'] )) {
				unset( $inputs['profilepic'] );
			}
			return Redirect::route( 'user.my-account' )
			               ->withInput( $inputs )
			               ->withErrors( $user->errors() )
			               ->with( 'flash_error', trans( "Your information could not be updated. Please try again." ) );
		}

		/* Profilepic upload to Amazon S3 */
		if (Input::hasFile( 'profilepic' )) {
			try {
				$user->profilepic = $this->uploadProfilePicToS3();
			} catch ( Exception $e ) {
				Log::error( $e );
				unset( $inputs['profilepic'] );
				return Redirect::route( 'user.my-account' )
				               ->withInput( $inputs )
				               ->with( 'flash_error',
					               trans( "There was a problem with your Profile Picture. Please try again." ) );
			}

			// Need to stop save validating the S3 filename, which will fail Laravel's image validation.
			//  Note: the original uploaded file has already passed Laravel's validation above
			$user->removeContext( 'profile-pic-upload' );
		}

		if ($user->save()) {
			Event::fire( 'user.account-updated', array( Auth::user() ) );
			return Redirect::route( 'user.my-account' )
			               ->with( 'flash_success', trans( "Your information has been updated" ) );
		} else {
			return Redirect::route( 'user.my-account' )
			               ->withInput( Input::all() )
			               ->withErrors( $user->errors() )
			               ->with( 'flash_error', trans( "Your information could not be updated. Please try again." ) );
		}
	}
}
