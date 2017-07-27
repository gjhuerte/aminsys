<?php

class SessionsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('pagenotfound');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('login');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if(Request::ajax()){
			$username = $this->sanitizeString(Input::get('username'));
			$password = $this->sanitizeString(Input::get('password'));
 		
			$user = array(	
				'username' => $username,
				'password' => $password
	 		);

			if(Auth::attempt($user))
			{
	 			return 'success';
	 		}else{
	 			return 'error';
	 		}
		}

		$username = $this->sanitizeString(Input::get('username'));
		$password = $this->sanitizeString(Input::get('password'));

 		$user = User::where('username','=',$username)->first();

 		if(count($user) == 0)
 		{
			Session::flash('error-message','Invalid login credentials');
			return Redirect::to('login');
 		}

 		if($user->status == '0')
 		{

			Session::flash('error-message','Account Inactive. Contact the administrator to activate your account');
			return Redirect::to('login');

 		}
 		
		$user = array(	
			'username' => $username,
			'password' => $password
 		);

		if(Auth::attempt($user))
		{
			return Redirect::to('/');
		}

		Session::flash('error-message','Invalid login credentials');
		return Redirect::to('login');

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{
		$person = Auth::user();
		return View::make('user.index')
			->with('person',$person);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit()
	{
		$user = Auth::user();
		return View::make('user.edit')
				->with("user",$user);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
		$lastname = $this->sanitizeString(Input::get('lastname'));
		$firstname = $this->sanitizeString(Input::get('firstname'));
		$middlename = $this->sanitizeString(Input::get('middlename'));
		$email = $this->sanitizeString(Input::get('email'));
		$password = $this->sanitizeString(Input::get('password'));
		$newpassword = $this->sanitizeString(Input::get('newpassword'));

		$user = User::find(Auth::user()->id);

		$validator = Validator::make([
			'Lastname'=>$lastname,
			'Firstname'=>$firstname,
			'Middlename'=>$middlename,
			'Email' => $email
		],User::$informationRules);

		if( $validator->fails() )
		{
			return Redirect::back()
				->withInput()
				->withErrors($validator);
		}

		if(!($password == "" && $newpassword == "")){


			$validator = Validator::make([
				'Current Password'=>$password,
				'New Password'=>$newpassword
			],User::$passwordRules);

			if( $validator->fails() )
			{
				return Redirect::back()
					->withInput()
					->withErrors($validator);
			}

			//verifies if password inputted is the same as the users password
			if(Hash::check($password,Auth::user()->password))
			{

				//verifies if current password is the same as the new password
				if(Hash::check($newpassword,Auth::user()->password)){
					Session::flash('error-message','Your New Password must not be the same as your Old Password');
					return Redirect::back()
						->withInput()
						->withErrors($validator);
				}else{

					$user->password = Hash::make($newpassword);
				}
			}else{

				Session::flash('error-message','Incorrect Password');
				return Redirect::back()
					->withInput();
			}

		}

		$user->firstname = $firstname;
		$user->middlename = $middlename;
		$user->lastname = $lastname;
		$user->email = $email;	
		$user->save();

		Session::flash('success-message','Information updated');
		return Redirect::back();
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy()
	{
		//remove everything from session
		Session::flush();
		//remove everything from auth
		Auth::logout();
		return Redirect::to('login');
	}

}
