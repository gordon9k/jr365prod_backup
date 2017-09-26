<?php

namespace jobready365\Http\Controllers;

// use jobready365\Http\Controllers\Auth\RegisterController;

use jobready365\User;
use jobready365\employer;
use jobready365\applicant;
use jobready365\Http\Controllers;

use Ramsey\Uuid\Uuid;
use jobready365\JR365;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
// use Illuminate\Auth;
use Auth;


class RegistrationController extends Controller
{
	protected $user;

	// use RegistersUsers;
	// protected $redirectTo = '/en/dashboard';

	public function __construct()
	{
		// $this->middleware('is.active')->only('confirm');
	}

	public function create()
	{
		return view('sessions.register');
	}

	public function store(Request $request)
	{
		$this->validate($request, [
            'telephone_no' => 'required|min:8|max:255|unique:users',
			'password' => 'required|min:4|confirmed',
			'user_type' => 'required',
        ]);

		$user_id = Uuid::uuid4()->getHex();
		$otp = rand(100000, 999999);

		$user = User::create([
			'id' => $user_id,
			'login_name' => '',
			'email' => '',
			'telephone_no' => request('telephone_no'),
			'password' => bcrypt(request('password')),
			'user_role' => 0,
			'is_active' => 0,
			'user_type' => request('user_type'),
			'activation_code' => $otp,
			'remember_token' =>	csrf_token()
			]);

		$sendConfirm = ['phone' => $user->telephone_no, 'otp' => $user->activation_code];
		
		JR365::sendOtp($sendConfirm);

		return redirect()->route('confirm', ['phone' => $user->telephone_no]);
	}

	// public function resendCode($user)
	// {
	// 	$user = User::where('id', $user)->firstOrFail();

	// 	// return $user;

	// 	JR365::resendOtp($user);

	// 	// return redirect()->back();
	// 	// return view('sessions.confirmation', compact('user'));
	// 	return redirect()->route('confirm', ['phone' => $user->telephone_no]);

	// }

	public function confirm($phone)
	{
		$user = User::where('telephone_no', $phone)->firstOrFail();

		// return $user;

		return view('sessions.confirmation', compact('user'));
	}

	public function confirmCode(Request $request)
	{
		$user = User::confirmation($request);

		if( is_null($user) ) {
			return back()->with('confirm_error', 'Your confirmation code is invalid!');
		} 
		
		$user->update(['is_active' => 1, 'activation_code' => '', 'expire_date' => null]);
		
		// return "All OK";
		$this->createInfo($user);
		
		// redirectTo = '/en/dashboard';
		// return $this->loginUsingId($user['id']);
		return redirect()->route('dashboard');

	}

	public function loginUsingId($id, $remember = false)
    {
        $user = $this->provider->retrieveById($id);

        if (! is_null($user)) {
            $this->login($user, $remember);

            return $user;
        }

        return false;
    }

	public function createInfo($user)
	{		
		$id = Uuid::uuid4()->getHex();
		//Log::info('user type'.$data['user_type']);
		if($user['user_type'] == 1) //employer

		{	$expire = date('Y-m-d h:i:s', strtotime("+168 hours", strtotime(date('Y-m-d H:i:s'))));
		employer::create([
			'id' => $id,
			'user_id' => $user['id'],
			'name'=>isset($user['login_name'])?$user['login_name']:'',
			'mobile_no' =>$user['telephone_no'],
			'email' => isset($user['email'])?$user['email']:'',
			'expired_date' => $expire,
					//'job_category_id'=>$user['category'],
			]);
		} else
		{ //applicant
			applicant::create([
				'id' => $id,
				'user_id' => $user['id'],
				'name'=>isset($user['login_name'])?$user['login_name']:'',
				'mobile_no' =>$user['telephone_no'],
				'email' => isset($user['email'])?$user['email']:'',
					//'job_category_id'=>$user['category'],
			]);
		}
	}


}
