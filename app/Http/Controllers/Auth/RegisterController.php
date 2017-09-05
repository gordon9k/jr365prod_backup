<?php

namespace jobready365\Http\Controllers\Auth;

use jobready365\User;
use jobready365\employer;
use jobready365\applicant;
use jobready365\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\RegistersUsers;
use Ramsey\Uuid\Uuid;
use Auth;
use Validator;

class RegisterController extends Controller
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
	protected $redirectTo = '/en/dashboard';
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
				'telephone_no' => 'required|max:255',
				'password' => 'required|min:6|confirmed',
				'user_type' => 'required',
				//'category'=>	'required',
		]);
	}
	
	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	protected function create(array $data)
	{	//print_r($data);
		//return $data;
		$user_id = Uuid::uuid4()->getHex();
		User::create([
				'id' => $user_id,
				'login_name' => isset($data['login_name'])?$data['login_name']:'',
				'email' => isset($data['email'])?$data['email']:'',
				'telephone_no' => $data['telephone_no'],
				'password' => bcrypt($data['password']),
				'user_role' => isset($data['user_role'])?$data['user_role']:0,
				'is_active' => 1,
				'user_type' => $data['user_type'],
				// 'category_id'=>$data['category'],
				'remember_token' =>	csrf_token()
		]);
		
		$this->createInfo($user_id,$data);		
		return Auth::loginUsingId($user_id);
	}
	
	public function createInfo($user_id, $data){
		
		$id = Uuid::uuid4()->getHex();
		//Log::info('user type'.$data['user_type']);
		if($data['user_type'] == 1) //employer
		{	$expire = date('Y-m-d h:i:s', strtotime("+168 hours", strtotime(date('Y-m-d H:i:s'))));
			employer::create([
					'id' => $id,
					'user_id' => $user_id,
					'name'=>isset($data['login_name'])?$data['login_name']:'',
					'mobile_no' =>$data['telephone_no'],
					'email' => isset($data['email'])?$data['email']:'',
					'expired_date' => $expire,
					//'job_category_id'=>$data['category'],
			]);
		}
		else{ //applicant
			applicant::create([
					'id' => $id,
					'user_id' => $user_id,
					'name'=>isset($data['login_name'])?$data['login_name']:'',
					'mobile_no' =>$data['telephone_no'],
					'email' => isset($data['email'])?$data['email']:'',
					//'job_category_id'=>$data['category'],
			]);
		}
	}
}