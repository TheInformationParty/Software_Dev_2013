<?php

class User_Controller extends Base_Controller{

	public $restful = true;

	public function get_register() {
		//TODO: redirect to home if the user is logged in
		return View::make('user.register')->with('title', 'Register!')->with('subtitle', '(Or '.HTML::link_to_route('login', 'login').' if you\'re already registered)');
	}

	public function get_login() {
		//TODO: redirect to home if the user is logged in
		return View::make('user.login')->with('title', 'Login!')->with('subtitle', '(Or '.HTML::link_to_route('register', 'register').', maybe?)');
	}

	public function get_logout() {
		if (Auth::check()) {
			Auth::logout();
			return Redirect::to_route('home')->with('message', 'You are now logged out!');
		} else {
			return Redirect::to_route('home');
		}
	}

	public function get_profile_view($id = null) {
		$user = User::find($id);
		return View::make('user.profile.view')->with('title', 'User profile')->with('subtitle', $user->username)->with('user', $user);
	}

	public function get_profile_update() {
		if (Auth::check()) {
			$user = Auth::user();
			return View::make('user.profile.update')->with('title', 'Update your profile')->with('subtitle', $user->username)->with('user', $user);	
		} else {
			//the user is not logged in, send them to login
			return Redirect::to_route('login')->with('message', 'Please login');
		}
	}

	public function post_register() {
		//TODO: Move to an add function in the user model

		//make sure password confirmation matches
		if(strcmp(Input::get('password'),Input::get('password_confirmation'))){
			return Redirect::to_route('register')->with('error','The passwords you entered didn\'t match')->with_input();
		}

		//get this user's zip's regions
		$zip = Input::get('postalcode');
		$postalcode = PostalCode::get_from_zip($zip);
		if(is_null($postalcode)){
			try {
				$postalcode = PostalCode::add($zip);
			} catch (Exception $e) {
				//redirect back to register page with error
				return Redirect::to_route('register')->with('error',$e->getMessage())->with_input();
			}
		}
		$regions = $postalcode->regions()->get();

		//create the user
		$user = new User();
		$user->username = Input::get('username');
		$user->email = Input::get('email');
		$user->password = Hash::make(Input::get('password'));
		$user->reputation = 0;
		$user->lasttermsofservice = "0.01"; //Needs a better implementation. Config file or something
		$user->postalcode_id = $postalcode->id;
		$user->firstname = Input::get('firstName');
		$user->lastname = Input::get('lastName');
		$user->description = Input::get('description');
		$user->gender = strtolower(Input::get('gender'));
		$user->birthdate = Input::get('birthdate');
		$user->race = strtolower(Input::get('race'));

		$validation = $user->validate();
		if ($validation->passes()){
			//try to parse links from description
			try {
	        	$links = MarkDown::parse_for_links($user->description);
	        } catch (Exception $e) {
	        	return Redirect::to_route('register')->with('error',$e->getMessage())->with_input();    
	        }
			try {
				$user->save();
			} catch (Exception $e) {
				return Redirect::to_route('register')->with('error',$e->getMessage())->with_input();
			}
			//add the regions to this user
			foreach($regions as $region){
				$user->regions()->attach($region->id);
			}
			//add the links to this user
			foreach($links as $link){
				$link->save();
				$user->links()->attach($link->id);
			}

			Auth::login($user);
			//send them home
			return Redirect::to_route('home')->with('message', 'Thanks for registering! You are now logged in. :]');
		} else {
			return Redirect::to_route('register')->with_errors($validation)->with_input();
		}
	}

	public function post_login() {
		$user = array(
			'username'=>Input::get('email'), //I know this is wierd, but it's required for Auth :(
			'password'=>Input::get('password')
		);

		if (Auth::attempt($user)) {
			return Redirect::to_route('home')->with('message', 'You\'re logged in! :]');
		} else {
			return Redirect::to_route('login')->with('message', 'Your username/password combination was incorrect')->with_input();
		}
	}

	public function post_profile_update() {
		if (Auth::check()) {
			$user = Auth::user();
			//if currentpassword != the user's password then send them out
			if(!Hash::check(Input::get('currentPassword'), $user->password)) {
				return Redirect::to_route('profile_update')->with('error', 'Your current password was incorrect!')->with_input();
			}

			$user->email = Input::get('email');
			$user->firstname = Input::get('firstName');
			$user->lastname = Input::get('lastName');
			$user->description = Input::get('description');
			$user->gender = Input::get('gender');
			$user->birthdate = Input::get('birthdate');
			$user->race = Input::get('race');
			//check to see if there is a new password
			$newPassword = Input::get('newPassword');
			if(!empty($newPassword)){
				//check to make sure the confirm is true
				if(strcmp($newPassword,Input::get('password_confirmation'))){
					$user->password = Hash::make($newPassword);
				}
			}

			//validate the user's input
			$validation = $user->validate();
			if ($validation->passes()){
				$zip = Input::get('postalcode');
				$postalcode = PostalCode::get_from_zip($zip);
				if(is_null($postalcode)){
					try {
						$postalcode = PostalCode::add($zip);
					} catch (Exception $e) {
						//redirect back to register page with error
						return Redirect::to_route('register')->with('error',$e->getMessage())->with_input();
					}
				}
				$user->save();

				//TODO: delete the old regions from this user before adding new ones
				$regions = $postalcode->regions()->get();
				foreach($regions as $region){
					$user->regions()->attach($region->id);
				}

				//TODO: remove old links then parse description for links
				//get the links from the description
				try {
		        	$links = MarkDown::parse_for_links($user->description);
		        } catch (Exception $e) {
		        	return Redirect::to_route('register')->with('error',$e->getMessage())->with_input();    
		        }
				//add the links to this user
				foreach($links as $link){
					$link->save();
					$user->links()->attach($link->id);
				}

				return Redirect::to_route('profile_update')->with('message', 'Profile successfully updated!');
			} else {
				return Redirect::to_route('profile_update')->with_errors($validation)->with_input();
			}
		} else {
			//the user is not logged in, send them to login
			return Redirect::to_route('login')->with('message', 'Please login');
		}
	}
}