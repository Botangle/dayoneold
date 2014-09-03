<?php

class RemindersController extends Controller {

	/**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
	public function getRemind()
	{
		return View::make('password.remind');
	}

	/**
	 * Handle a POST request to remind a user of their password.
	 *
	 * @return Response
	 */
	public function postRemind()
	{
        $response = Password::remind(Input::only('email'), function($message){
            $message->subject('[Botangle] Reset Password');
        });
		switch ($response)
		{
			case Password::INVALID_USER:
				return Redirect::back()->with('flash_error', Lang::get($response));

			case Password::REMINDER_SENT:
                $user = User::where('email', Input::get('email'))->first();
                if ($user){
                    Event::fire('user.password-reset-request', array($user));
                }
				return Redirect::back()->with('flash_success',
                    trans("Password reset email sent. If you do not soon receive this email, please check your junk mail folder. If you still cannot locate the email, please reach out to contactus@botangle.com.")
                );
		}
	}

	/**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return Response
	 */
	public function getReset($token = null)
	{
		if (is_null($token)) App::abort(404);

		return View::make('password.reset')->with('token', $token);
	}

	/**
	 * Handle a POST request to reset a user's password.
	 *
	 * @return Response
	 */
	public function postReset()
	{
		$credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$response = Password::reset($credentials, function($user, $password)
		{
            // Don't need to Hash the password otherwise Magniloquent will do it again!
			$user->password = $password;

            // However, if the user is resetting their password to the same value
            //  (why? No idea, but it could happen) then Magniloquent's autohasher messes up and saves
            //  out the plain password.
            // So, putting in a workaround here. No need to save if the password hasn't actually been changed
            if (!Hash::check($password, $user->getOriginal('password'))){
                $user->save();
            }
		});

		switch ($response)
		{
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
                $user = User::where('email', Input::get('email'))->first();
                if ($user){
                    Event::fire('user.password-reset-failure', array($user));
                }
				return Redirect::back()->with('flash_error', Lang::get($response));

			case Password::PASSWORD_RESET:
                $user = User::where('email', Input::get('email'))->first();
                if ($user){
                    Event::fire('user.password-reset-success', array($user));
                }
				return Redirect::route('login')
                    ->with('flash_success', trans('Password reset successfully. Please login with your new password'));
		}
	}

}
