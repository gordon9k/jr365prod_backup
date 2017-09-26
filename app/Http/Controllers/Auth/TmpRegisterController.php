<?php

namespace jobready365\Http\Controllers\Auth;

use jobready365\User;
use jobready365\TmpUsers;
use jobready365\employer;
use jobready365\applicant;
use jobready365\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\RegistersUsers;
use Ramsey\Uuid\Uuid;
use Auth;
use Validator;

class TmpRegisterController extends Controller
{
	/*
	 |--------------------------------------------------------------------------
	 | Register Controller
	 |--------------------------------------------------------------------------
	 |
	 | This controller handles the registration of new users as well as their
	 | validation and creation. By default this controller uses a trait to
	 | provide this functionality without requiring any additional code.
	 |
	 */
	
	use RegistersUsers;
	
	/**
	 * Where to redirect users after login / registration.
	 *
	 * @var string
	 */
	//protected $redirectTo = '/home/register';
	protected $redirectTo = '/en/confirmation';
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}
	
	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
				//'login_name' => 'required|max:255|unique:users',
				'telephone_no' => 'required|min:8|max:255|unique:users',
				'password' => 'required|min:6|confirmed',
				'user_type' => 'required',
				//'category'=>	'required',
		]);
	}

	public function create(array $data) {

		$user_id = Uuid::uuid4()->getHex();
		// $data = $request->all();
		$otp = rand(100000, 999999);
		dd($data, $user_id, $otp);

		TmpUsers::create([
			'id' => $user_id,
			'telephone_no' => $data['telephone_no'],
			'password' => bcrypt($data['password']),
			'user_role' => isset($data['user_role'])?$data['user_role']:0,
			'is_active' => 1,
			'user_type' => $data['user_type'],
			'activation_code' => $otp,
			'remember_token' => csrf_token()
		]);

		// $jobs = $this->$data;
		// return view('auth.confirmation', compact('jobs'));
	}
}
