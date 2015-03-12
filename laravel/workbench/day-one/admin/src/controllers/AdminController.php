<?php namespace DayOne\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
	protected $layout = "administrator::layouts.default";

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
			$this->layout->page = false;
			$this->layout->dashboard = false;
		}
	}

	public function getLogin()
	{
		$this->layout->content = View::make("admin::login");
	}

	public function postLogin()
	{
		$this->layout = null;
		$username = Input::get('username');
		$password = Input::get('password');

		$user = \User::active()->where('username', $username)->first();
		if ($user){
			$loggedIn = Auth::attempt(array(
				'username' => $username,
				'password' => $password,
			));
		}

		// we check the admin status of this user here to try and protect against timing attacks
		// if we do all our calculations post DB then it's harder to tell if a user account is failing
		// out because the user isn't an admin or because the password isn't valid ...
		if (!$user || !$loggedIn || !$user->isAdmin()){

			return Redirect::route('backdoor_login')
			               ->with('flash_error', trans('Your username or password is incorrect.'))
			               ->withInput();
		}

		// redirect to our backend system
		return Redirect::to('/backend');
	}
}
