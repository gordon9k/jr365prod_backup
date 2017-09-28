<?php

namespace jobready365\Http\Controllers;

use Illuminate\Http\Request;
use jobready365\User;
use jobready365\employer;
use jobready365\job;
use jobready365\job_category;
use jobready365\job_nature;
use jobready365\city;
use jobready365\township;
use jobready365\applicant;
use jobready365\experience;
use jobready365\education;
use jobready365\qualification;
use jobready365\skill;
use jobready365\refree;
use jobready365\company;
use jobready365\application;
use jobready365\candidate;
use jobready365\JR365;

use Ramsey\Uuid\Uuid;

use Response;
use Log;
use Input;
use DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiController extends Controller
{	
	public function randomId()
	{	
		$string = "";
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
		//$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

		for($i=0;$i<10;$i++)
			$string.=substr($chars,rand(0,strlen($chars)),1);
		return $string;
	}
	
	public function authenticate(Request $request)
	{
		// grab credentials from the request
		$credentials = $request->only('telephone_no', 'password');
		//Log::info('credentials : '.$credentials );
		try {
			// attempt to verify the credentials and create a token for the user
			if (! $token = JWTAuth::attempt($credentials)) {
				//return response()->json(['error' => 'invalid_credentials'], 401);
				return response()->json(['error' => true, 'result'=>"User name & Password does not matched",'status_code' => 210]);
			}
		} catch (JWTException $e) {
			// something went wrong whilst attempting to encode the token
			//return response()->json(['error' => 'could_not_create_token'], 500);
			return response()->json(['error' => true, 'result'=>"could_not_create_token",'status_code' => 210]);
		}
		Log::info('login success: '.$token);
		try{
			DB::update("update users set api_token = '".$token."' where telephone_no = '".$request->only('telephone_no')['telephone_no']."'");
			$user =DB::table('users')
			->select('*')
			->where('api_token', $token)
			->get();
			
			if($user[0]->user_type == 1){
				$employer=DB::table('employers as emp')
				->join('users as u','u.id','=','emp.user_id')
				->select('emp.*')
				->where('u.telephone_no', $request->only('telephone_no'))
				->get();
				
				
				$current = date('Y-m-d H:i:s');
				$expire = $employer[0]->expired_date;
				if($expire != '' && $expire  != null){	
					$remain_time = round((strtotime($expire) - strtotime($current)) /3600);
					
					$user[0]->remain_time = $remain_time > 0 ? $remain_time .' Hr': 'Expire';
				}
				$user[0]->package_type = $employer[0]->package_type;
			}
		}catch(Exception $e){
			return response()->json(['error' => true, 'result' => 'cannot_save_token_in_table', 'status_code' => 210]);
		}
		// all good so return the token
		$status = $status[0]=200;
		return response()->json(compact('token','user','status'));
		
	}
	
	public function registerUser(Request $request)
	{	Log::info('user register start');
	try{
		$data = $request->all();

		$user_id = Uuid::uuid4()->getHex();
		$otp = rand(100000, 999999);			
			//$activation_code = $this->randomId();

		try{
			$user = User::create([
				'id' => $user_id,
				'login_name' => $data['login_name'],
				'email' => $data['email'],
				'telephone_no'=>$data['telephone_no'],
				'password' => bcrypt($data['password']),
				'user_role' => 0,
				'is_active' => 0,
				'user_type' => $data['user_type'],
				'activation_code' => $otp,
				'remember_token' => csrf_token()
			]);

			// return $user;

			// $sendConfirm = ['phone' => $user->telephone_no, 'otp' => $user->activation_code];

			// JR365::sendOtp($sendConfirm);

		}catch(\Exception $e){
			$errorCode = $e->errorInfo[1];
			Log::info('$errorCode '.$e);				
				//if($errorCode == 1062){
			return response()->json(['error' => true, 'result'=>'Duplicate telephone number on register user', 'status_code' => 210]);	
				//}
		}
		// app('jobready365\Http\Controllers\RegistrationController')->createInfo($user_id, $data);
		app('jobready365\Http\Controllers\RegistrationController')->createInfo($user_id, $data);
		Log::info('user register ok');
	}catch(\Exception $ex){
		Log::info('user register fail'.$ex);
		return response()->json(['error' => true, 'result'=>'Error in register user', 'status_code' => 210]);
	}

	return $this->confirmation($request);
	// return $this->authenticate($request);

		//$token = $this->authenticate($request);
		//return response()->json(['error' => false, 'result'=>true, 'activation_code'=>$activation_code, 'status_code' => 200]);
}

	// city api //
public function getAllCity_api()
{   $city = city::orderBy('city')->get();
if($city != null)   {$return_code = 200; $msg = $city;}
else {   $return_code = 210; $msg = "No Record Found!";  }

return Response::json(array(
	'error' => false,
	'city' => $msg,
	'status_code' => $return_code
	));
}

	// township api //
public function getAllTownship_api($city_id = '')
{
	if($city_id == null)
		$tsp= township::orderBy('township')->get();
	else
		$tsp= township::where("city_id",$city_id)->orderBy('township')->get();

	if($tsp!= null)   {$return_code = 200; $msg = $tsp;}
	else {   $return_code = 210; $msg = "No Record Found!";  }

	return Response::json(array(
		'error' => false,
		'township' => $msg,
		'status_code' => $return_code
		));
}

	// job_type api //
public function getAllJobType_api()
{
	$job_type = job_nature::orderBy('type')->get();
	if($job_type != null)   {$return_code = 200; $msg = $job_type;}
	else {   $return_code = 210; $msg = "No Record Found!";  }

	return Response::json(array(
		'error' => false,
		'jobtype' => $msg,
		'status_code' => $return_code
		));
}

	// job_category api //
public function getAllJobCategory_api()
{
	$job_category= app('jobready365\Http\Controllers\JobCategoryController')->getAllJobCategoryList();
	if($job_category != null)   {$return_code = 200; $msg = $job_category;}
	else {   $return_code = 210; $msg = "No Record Found!";  }

	return Response::json(array(
		'error' => false,
		'jobcategory' => $msg,
		'status_code' => $return_code
		));
}

	// job_industry api //
public function getAllJobIndustry_api($type=0)
{	
	$job_industry = app('jobready365\Http\Controllers\JobIndustryController')->getAllJobIndustryList($type);
	if($job_industry!= null)   {$return_code = 200; $msg = $job_industry;}
	else {   $return_code = 210; $msg = "No Record Found!";  }

	return Response::json(array(
		'error' => false,
		'jobindustry' => $msg,
		'status_code' => $return_code
		));
}

public function getAllJobbySearch_api()
{
	$salary_range= Input::get("salary_range");
	$township = Input::get("township");
	$type = Input::get("type");
	$category = Input::get("category");
	$company = Input::get("company");
	$data = array(
		'salary_range' => $salary_range,
		'township'=>$township,
		'type' => $type,
		'category' => $category,
		'company'=>$company
		);
	$result = app('jobready365\Http\Controllers\JobController')->getLatestJobbyKeyword($data);
	if($result != null)   {$return_code = 200; $msg = $result;}
	else {   $return_code = 210; $msg = "No Record Found!";  }
		//$return_code = 200; $msg = $result;
	return Response::json(array(
		'error' => false,
		'job' => $msg,
		'status_code' => $return_code
		));
}

public function getRecentJob_api()
{
	$result = app('jobready365\Http\Controllers\JobController')->getRecentJob();
	if($result != null)   {$return_code = 200; $msg = $result;}
	else {   $return_code = 210; $msg = "No Record Found!";  }
		//$return_code = 200; $msg = $result;
	return Response::json(array(
		'error' => false,
		'job' => $msg,
		'status_code' => $return_code
		));
}

public function getJobCategorybyId_api($id)
{
		//$job_category = $this->getJobCategorybyId($id);
	$job_category= app('jobready365\Http\Controllers\JobCategoryController')->getJobCategorybyId($id);
	if($job_category != null)   {$return_code = 200; $msg = $job_category;}
	else {   $return_code = 210; $msg = "No Record Found!";  }

	return Response::json(array(
		'error' => false,
		'result' => $msg,
		'status_code' => $return_code
		));
}

public function updateEmployer_api($id)
{
	try{
		$employer = employer::findOrFail($id);
		app('jobready365\Http\Controllers\EmployerController')->save_employer_data($employer);
	}catch (\Exception $e){
		Log::info('duplicate employer: '.$e);
		$errorCode = $e->errorInfo[1];
		if($errorCode == 1062){
			return Response::json(array(
				'error' => false,
				'result' => 'Fail to Update',
				'status_code' => 210));
		}
	}
	return Response::json(array(
		'error' => false,
		'result' => 'Success',
		'status_code' => 200
		));
}

public function getEmployerbyId_api($id)
{
	$employer = app('jobready365\Http\Controllers\EmployerController')->getEmployerbyId($id,0);
	if($employer != null)   {$return_code = 200; $msg = $employer;}
	else {   $return_code = 210; $msg = "No Record Found!";  }

	return Response::json(array(
		'error' => false,
		'employer' => $msg,
		'status_code' => $return_code
		));
}

public function checkValidEmployerbyUserId_api($uid)
{	Log::info('user id checkValidEmployerbyUserId: '.$uid);
$employer = app('jobready365\Http\Controllers\EmployerController')->checkValidEmployerbyUserId($uid);
Log::info('checkValidEmployerbyUserId: '.$employer);
Log::info('checkValidEmployerbyUserId count: '.count($employer));
if(count($employer) == 1)   {$return_code = 200; $msg = 1;}
else {   $return_code = 200; $msg = 0;  }

return Response::json(array(
	'error' => false,
	'employer' => $msg,
	'status_code' => $return_code
	));
}

public function getApplicantbyId_api($uid, $jid = 0)
{
	$applicant = app('jobready365\Http\Controllers\ApplicantController')->showApplicantbyIdAPI($uid,$jid,0);

	if($applicant != null)
		return Response::json(array('error' => false,'applicant' => $applicant,'status_code' => 200));
	else
		return Response::json(array('error' => false,'applicant' => "No Record Found!",'status_code' => 210));
}

public function updateApplicant_api($id)
{
	Log::info('updateApplicant api id'.$id);

	try {
		$applicant = applicant::findOrFail($id);
		$save_status = app('jobready365\Http\Controllers\ApplicantController')->save_applicant_data($applicant);

		Log::info('status save applicant: '.$save_status);
		if ($save_status == 1)	{	Log::info('save success: ');
		return Response::json(array(
			'error' => false, 'result' => 'Success', 'status_code' => 200
			));
	}else if ($save_status == 'over')	{	Log::info('date is over: ');
	return Response::json(array(
		'error' => false, 'result' => 'Selected Date of Birth is greater than Today !!!', 'status_code' => 200
		));
}
else{ Log::info('save fail: ');
return Response::json(array(
	'error' => false, 'result' => 'Fail to Save', 'status_code' => 210
	));
}
} catch (\Exception $e) {
	Log::info('error on update applicant: '.$e);
	return Response::json(array(
		'error' => false,
		'result' => 'Fail to update',
		'status_code' => 210
		));
}

}

public function uploadPicture_api($id)
{   $val = '';
try{
	$applicant = applicant::findOrFail($id);
	$val = app('jobready365\Http\Controllers\ApplicantController')->upload_picture($applicant);
			//print_r($val);
			////$val = json_decode($val);
			//echo $val['last_id'];
			//echo $val['resume-photo'];
	$resume_photo = '';
	if($val == ""){
		$error = true; $result= "Fail in Upload Profile Picture. There is no photo to upload"; $last_id = ""; $status = 210;
	}
	else{
		if($val == "invalid file type"){
			$error = true; $result = "File Extension is not allowed, please choose a JPEG or PNG file."; $last_id = ""; $status = 210;
		}
		else if($val == "invalid file size"){
			$error = true; $result = "File size must be less than 5 MB"; $last_id = ""; $status = 210;
		}
		else{
			$error = false; $result = "Success"; $last_id = $val['last_id']; $resume_photo = $val['resume-photo']; $status = 200;
		}
	}

	return Response::json(array(
		'error' => $error , 'result' => $result, 'last_id' => $last_id, 'resume-photo'=>$resume_photo,  'status_code' => $status
		));
}catch (\Exception $e){
	Log::info('error in upload profile picture: '.$e);
	$errorCode = $e->errorInfo[1];
	if($errorCode == 1062){
		return Response::json(array(
			'error' => false, 'result' => 'Error on Upload Profile Picture', 'status_code' => 210));
	}
}	
}

public function uploadCV_api($id)
{   $val = '';
try{
	$applicant = applicant::findOrFail($id);
	$val = app('jobready365\Http\Controllers\ApplicantController')->upload_CV($applicant);

}catch (\Exception $e){
	Log::info('error in upload profile picture: '.$e);
	$errorCode = $e->errorInfo[1];
	if($errorCode == 1062){
		return Response::json(array(
			'error' => false, 'result' => 'Fail to Upload CV', 'status_code' => 210));
	}
}

if($val == ""){
	$error = true; $result= "Fail in Upload CV. There is no CV file to upload"; $last_id = ""; $status = 210;
}
else{
	if($val == "invalid file type"){
		$error = true; $result = "File Extension is not allowed, please choose a PDF or DOCX file."; $last_id = ""; $status = 210;
	}
	else if($val == "invalid file size"){
		$error = true; $result = "File size must be less than 5 MB"; $last_id = ""; $status = 210;
	}
	else{
		$error = false; $result = "Success"; $last_id = $val; $status = 200;
	}
}

return Response::json(array(
	'error' => $error , 'result' => $result, 'last_id' => $last_id, 'status_code' => $status
	));
}

	// education api //
public function getEducationbyUserId_api($uid)
{
	$result = app('jobready365\Http\Controllers\EducationController')->getAllEducation($uid);
	return Response::json(array(
		'error' => false,
		'education' => $result,
		'status_code' => 200
		));
}

public function insertEducation_api()
{
	try{
		Log::info("education user id: ".Input::get('user_id'));
		$edu = new education();
		$edu->applicant_id = Input::get('user_id');
		$edu->id = Uuid::uuid4()->getHex();
		$ret = app('jobready365\Http\Controllers\EducationController')->save_education_data($edu);
		Log::info("ret: ".$ret);
		if($ret == 1){
			$result = "Success";
		}
		else if($ret == 'invalid'){
			$result = 'Start Date is greater than end date';
		}
		else if($ret == 'over'){
			$result = 'Selected Date is greater than Today !!!';
		}
		return Response::json(array(
			'error' => false,
			'result' => $result,
			'status_code' => 200
			));
	}catch (\Exception $e){
		Log::info('error is save education api: '.$e);
		return Response::json(array(
			'error' => true,
			'result' => 'Error in Save Education',
			'status_code' => 210
			));
	}
}

public function deleteEducation_api($id)
{
	try{
		$ret = app('jobready365\Http\Controllers\EducationController')->deleteEducationbyId($id,0);

		if($ret != 0)
			return Response::json(array(
				'error' => false,
				'result' => 'Success',
				'status_code' => 200
				));
		else
			return Response::json(array(
				'error' => true,
				'result' => 'No record to delete',
				'status_code' => 210
				));
	}catch (\Exception $e){
		Log::info('error is delete education api: '.$e);
		return Response::json(array(
			'error' => true,
			'result' => 'Fail to delete',
			'status_code' => 210
			));
	}
}

public function getEducationbyId_api($id)
{
	$result = app('jobready365\Http\Controllers\EducationController')->getEducation($id);
	return Response::json(array(
		'error' => false,
		'education' => $result,
		'status_code' => 200
		));
}

public function updateEducationbyId_api($id)
{
	$ret = app('jobready365\Http\Controllers\EducationController')->updateEducationbyId($id,0);
	if($ret == 'invalid'){
		$result = 'Start Date is greater than end date';
	}
	else if($ret == 'over'){
		$result = 'Selected Date is greater than Today !!!';
	}
	else $result = "Success";
	return Response::json(array(
		'error' => false,
		'result' => $result ,
		'status_code' => 200
		));
}

	// qualification api //
public function getQualificationbyUserId_api($uid)
{
	$result = app('jobready365\Http\Controllers\QualificationController')->getAllQualification($uid);
	return Response::json(array(
		'error' => false,
		'qualification' => $result,
		'status_code' => 200
		));
}

public function insertQualification_api()
{
	try{
		$qua = new qualification();
		$qua->applicant_id = Input::get('user_id');
		$qua->id = Uuid::uuid4()->getHex();
		$ret = app('jobready365\Http\Controllers\QualificationController')->save_qualification_data($qua);
		if($ret == 'invalid'){
			$result = 'Start Date is greater than end date';
		}
		else if($ret == 'over'){
			$result = 'Selected Date is greater than Today !!!';
		}
		else $result = "Success";
		return Response::json(array(
			'error' => false,
			'result' => $result,
			'status_code' => 200
			));
	}catch (\Exception $e){
		Log::info('error is save qualification api: '.$e);
		return Response::json(array(
			'error' => true,
			'result' => 'Error in Save Qualification',
			'status_code' => 210
			));
	}
}

public function deleteQualification_api($id)
{
	try{
		$ret = app('jobready365\Http\Controllers\QualificationController')->deleteQualificationbyId($id,0);

		if($ret != 0)
			return Response::json(array(
				'error' => false,
				'result' => 'Success',
				'status_code' => 200
				));
		else
			return Response::json(array(
				'error' => true,
				'result' => 'No record to delete',
				'status_code' => 210
				));
	}catch (\Exception $e){
		Log::info('error is delete qualification api: '.$e);
		return Response::json(array(
			'error' => true,
			'result' => 'Fail to delete',
			'status_code' => 210
			));
	}
}

public function getQualificationbyId_api($id)
{
	$result = app('jobready365\Http\Controllers\QualificationController')->getQualification($id);
	return Response::json(array(
		'error' => false,
		'qualification' => $result,
		'status_code' => 200
		));
}

public function updateQualificationbyId_api($id)
{
	$ret = app('jobready365\Http\Controllers\QualificationController')->updateQualificationbyId($id,0);
	if($ret == 'invalid'){
		$result = 'Start Date is greater than end date';
	}
	else if($ret == 'over'){
		$result = 'Selected Date is greater than Today !!!';
	}
	else $result = "Success";
	return Response::json(array(
		'error' => false,
		'result' => $result,
		'status_code' => 200
		));
}

	// skill api //
public function getSkillbyUserId_api($uid)
{
	$result = app('jobready365\Http\Controllers\SkillController')->getAllSkill($uid);
	Log::info('getSkillbyUserId: '.$result);
	return Response::json(array(
		'error' => false,
		'skill' => $result,
		'status_code' => 200
		));
}

public function insertSkill_api()
{
	try{
		$skill = new skill();
		$skill->id = Uuid::uuid4()->getHex();
		$skill->user_id = Input::get('user_id');
		$ret = app('jobready365\Http\Controllers\SkillController')->save_skill_data($skill);			
	}catch (\Exception $e){
		Log::info('error is save skill api: '.$e);
		return Response::json(array(
			'error' => false,
			'result' => 'Fail to Save',
			'status_code' => 210
			));
	}
	return Response::json(array(
		'error' => false,
		'result' => 'Success',
		'status_code' => 200
		));
}

public function deleteSkill_api($id)
{
	try{
		$ret = app('jobready365\Http\Controllers\SkillController')->deleteSkillbyId($id,0);
		if($ret != 0)
			return Response::json(array(
				'error' => false,
				'result' => 'Success',
				'status_code' => 200
				));
		else
			return Response::json(array(
				'error' => true,
				'result' => 'No record to delete',
				'status_code' => 210
				));
	}catch (\Exception $e){
		Log::info('error is delete skill api: '.$e);
		return Response::json(array(
			'error' => false,
			'result' => 'Fail to delete',
			'status_code' => 210
			));
	}
}

public function getSkillbyId_api($id)
{
	$result = app('jobready365\Http\Controllers\SkillController')->getSkill($id);
	return Response::json(array(
		'error' => false,
		'skill' => $result,
		'status_code' => 200
		));
}

public function updateSkillbyId_api($id)
{
	$result = app('jobready365\Http\Controllers\SkillController')->updateSkillbyId($id,0);
	return Response::json(array(
		'error' => false,
		'result' => 'success',
		'status_code' => 200
		));
}

	// experience api //
public function getExperiencebyUserId_api($uid)
{
	$result = app('jobready365\Http\Controllers\ExperienceController')->getAllExperience($uid);
	return Response::json(array(
		'error' => false,
		'experience' => $result,
		'status_code' => 200
		));
}

public function insertExperience_api()
{
	try{
		$exp = new experience();
		$exp ->id = Uuid::uuid4()->getHex();
		$exp->user_id = Input::get('user_id');

		$ret = app('jobready365\Http\Controllers\ExperienceController')->save_experience_data($exp);
		Log::info('ret_experience_data : '.$ret);

		if($ret == 1){ Log::info('success');
		return Response::json(array(
			'error' => false,
			'result' => 'Success',
			'status_code' => 200
			));
	}
	if($ret == 'invalid'){ Log::info('invalid');
	return Response::json(array(
		'error' => false,
		'result' => 'Start Date is greater than end date',
		'status_code' => 200
		));
}
else if($ret == 'over'){ Log::info('over');
return Response::json(array(
	'error' => false,
	'result' => 'Selected Date is greater than Today !!!',
	'status_code' => 200
	));
}


}catch (\Exception $e){
	Log::info('error is save experience api: '.$e);
	return Response::json(array(
		'error' => false,
		'result' => 'Fail to Save',
		'status_code' => 210
		));
}
}

public function deleteExperience_api($id)
{
	try{
		$ret = app('jobready365\Http\Controllers\ExperienceController')->deleteExperiencebyId($id,0);
		if($ret != 0)
			return Response::json(array(
				'error' => false,
				'result' => 'Success',
				'status_code' => 200
				));
		else
			return Response::json(array(
				'error' => true,
				'result' => 'No record to delete',
				'status_code' => 210
				));
	}catch (\Exception $e){
		Log::info('error is delete experience api: '.$e);
		return Response::json(array(
			'error' => false,
			'result' => 'Fail to delete',
			'status_code' => 210
			));
	}
}

public function getExperiencebyId_api($id)
{
	$result = app('jobready365\Http\Controllers\ExperienceController')->getExperience($id);
	return Response::json(array(
		'error' => false,
		'experience' => $result,
		'status_code' => 200
		));
}

public function updateExperiencebyId_api($id)
{
	$ret= app('jobready365\Http\Controllers\ExperienceController')->updateExperiencebyId($id,0);
	Log::info('ret_update_experience_data : '.$ret);
	if($ret == 1){ Log::info('success');
	return Response::json(array(
		'error' => false,
		'result' => 'Success',
		'status_code' => 200
		));
}
if($ret == 'invalid'){ Log::info('invalid');
return Response::json(array(
	'error' => false,
	'result' => 'Start Date is greater than end date',
	'status_code' => 200
	));
}
else if($ret == 'over'){ Log::info('over');
return Response::json(array(
	'error' => false,
	'result' => 'Selected Date is greater than Today !!!',
	'status_code' => 200
	));
}
}

	// refree api //
public function getRefreebyUserId_api($uid)
{
	$result = app('jobready365\Http\Controllers\RefreeController')->getAllRefree($uid);
	return Response::json(array(
		'error' => false,
		'refree' => $result,
		'status_code' => 200
		));
}

public function insertRefree_api()
{
	try{
		$refree = new refree();
		$refree->applicant_id = Input::get('user_id');
		app('jobready365\Http\Controllers\RefreeController')->save_refree_data($refree);
	}catch (\Exception $e){
		Log::info('error is save refree api: '.$e);
		return Response::json(array(
			'error' => false,
			'result' => 'Fail to Save',
			'status_code' => 210
			));
	}
	return Response::json(array(
		'error' => false,
		'result' => 'Success',
		'status_code' => 200
		));
}

public function deleteRefree_api($id)
{
	try{
		$ret = app('jobready365\Http\Controllers\RefreeController')->deleteRefreebyId($id,0);
		if($ret != 0)
			return Response::json(array(
				'error' => false,
				'result' => 'Success',
				'status_code' => 200
				));
		else
			return Response::json(array(
				'error' => true,
				'result' => 'No record to delete',
				'status_code' => 210
				));
	}catch (\Exception $e){
		Log::info('error is delete experience api: '.$e);
		return Response::json(array(
			'error' => false,
			'result' => 'Fail to delete',
			'status_code' => 210
			));
	}
}

	// company api //
public function getAllCompanybyUserID_api($uid)
{
	$company = app('jobready365\Http\Controllers\CompanyController')->getAllCompanybyUserID($uid);
	if($company != null)   {$return_code = 200; $msg = $company ;}
	else {   $return_code = 210; $msg = "No Record Found!";  }

	return Response::json(array(
		'error' => false,
		'user_company' => $msg,
		'status_code' => $return_code
		));
}

public function getCompanybyID_api($id)
{	Log::info('get company api '.$id);
$company = app('jobready365\Http\Controllers\CompanyController')->getCompanybyId($id,0);

if($company != null)   {$return_code = 200; $msg = $company ;}
else {   $return_code = 210; $msg = "No Record Found!";  }

return Response::json(array(
	'error' => false,
	'company' => $msg,
	'status_code' => $return_code
	));
}

public function insertCompany_api()
{	Log::info('insert company api');
$val = '';
try{
	$company = new  company;
	$company->id = Uuid::uuid4()->getHex();
	$company->user_id = Input::get('user_id');
	$company->type =  Input::get('type');
			//$validator=app('jobready365\Http\Controllers\CompanyController')->validate_data();
			/*if ($validator->fails()) {
				return Response::json(array(
						'error' => false,
						'result' => 'Required fields is null',
						'status_code' => 220
				));
			}
			else{*/
				Log::info('save api start');
				$val = app('jobready365\Http\Controllers\CompanyController')->save_company_data($company);
		//	}
			}catch (\Exception $e){
				Log::info('error in save company: '.$e);
				$errorCode = $e->errorInfo[1];
				if($errorCode == 1062){
					return Response::json(array(
						'error' => false,
						'result' => 'Fail to Save',
						'status_code' => 210
						));
				}
			}
			if($val == -1)
				return Response::json(array(
					'error' => true,
					'result' => 'Fail in Save',
					'last_id' => $val,
					'status_code' => 210
					));
			else
				return Response::json(array(
					'error' => false,
					'result' => 'Success',
					'last_id' => $val,
					'status_code' => 200
					));
		}

		public function updateLogo_api($id = "0")
		{   	$val = '';
		Log::info('input company id: '.$id);
		try{
			if($id == "0") {
				Log::info('new');
				$company = new  company;
				$company->id = Uuid::uuid4()->getHex();
				$company->user_id = Input::get('user_id');
			}
			else{Log::info('edit');
			$company = company::findOrFail($id);
		}
		$val = app('jobready365\Http\Controllers\CompanyController')->upload_logo($company);
	}catch (\Exception $e){
		Log::info('error in upload Logo: '.$e);
		return Response::json(array(
			'error' => true, 'result' => 'Error in Upload Logo', 'status_code' => 210));
	}
	Log::info('val '.$val);
	if($val == ""){
		$error = true; $result= "Fail in Upload Logo. There is no photo to upload"; $last_id = ""; $status = 210;
	}
	else{
		if($val == "invalid file type"){
			$error = true; $result = "File Extension is not allowed, please choose a JPEG or PNG file."; $last_id = ""; $status = 210;
		}
		else if($val == "invalid file size"){
			$error = true; $result = "File size must be less than 5 MB"; $last_id = ""; $status = 210;
		}
		else{
			$error = false; $result = "Success"; $last_id = $val; $status = 200;
		}
	}

	return Response::json(array(
		'error' => $error , 'result' => $result, 'last_id' => $last_id, 'status_code' => $status
		));
}

public function updateCompany_api($id)
{   $val = '';
try{

	$company = company::findOrFail($id);

	$val = app('jobready365\Http\Controllers\CompanyController')->save_company_data($company);

}catch (\Exception $e){
	Log::info('error in update employer: '.$e);
	$errorCode = $e->errorInfo[1];
	if($errorCode == 1062){
		return Response::json(array(
			'error' => false,'result' => 'Fail to Update','status_code' => 210
			));
	}
}
if($val == '')
	return Response::json(array(
		'error' => true, 'result' => 'Fail in Save', 'last_id' => $val, 'status_code' => 210
		));
else
	return Response::json(array(
		'error' => false, 'result' => 'Success', 'last_id' => $val, 'status_code' => 200
		));
}

public function deleteCompany_api($id)
{	Log::info('delete company');
$ret = app('jobready365\Http\Controllers\CompanyController')->deleteCompanybyId($id,0);
Log::info('delete company finish'.$ret);
if($ret == 1) {    $return_code = 200; $msg = "Successfully Deleted!";  }
else {   $return_code = 210; $msg = "Delete Fail!";  }

return Response::json(array(
	'error' => false, 'result' => $msg, 'status_code' => $return_code
	));
}

	// job api  //
public function getAllJob_api()
{	Log::info('getAlljob job ');
$job = app('jobready365\Http\Controllers\JobController')->getAllJob();
if($job != null)   {$return_code = 200; $msg = $job;}
else {   $return_code = 210; $msg = "No Record Found!";  }

return Response::json(array(
	'error' => false,
	'job' => $msg,
	'status_code' => $return_code
	));
}

public function getAllJobCount_api()
{	
	$job = app('jobready365\Http\Controllers\JobController')->getAllJobCount();
	if($job != null)   {$return_code = 200; $msg = $job[0]->job_count;}
	else {   $return_code = 210; $msg = "No Record Found!";  }

	return Response::json(array(
		'error' => false,
		'job_count' => $msg,
		'status_code' => $return_code
		));
}

public function insertJob_api()
{	Log::info('insert job api');
try{
	$job = new job;
	$job->id = Uuid::uuid4()->getHex();
	$job->employer_id = Input::get('employer_id');
	$job->is_active = 0;

	Log::info('open date '. Input::get('open_date'));
	Log::info('close date '. Input::get('close_date'));

	if(Input::get('open_date') != null && Input::get('close_date') != null){
		$open_date = date_format(date_create(Input::get('open_date')), 'Y-m-d');
		$close_date = date_format(date_create(Input::get('close_date')), 'Y-m-d');
		if($open_date > $close_date) {
			return Response::json(array(
				'error' => false, 'result' => 'Open Date is greater than Close Date !!!', 'status_code' => 200
				));
		}
	}

	$job->open_date = Input::get('open_date')!=null ? date_format(date_create(Input::get('open_date')), 'Y-m-d') : date("Y-m-d");
	$expire = date('Y-m-d',strtotime($job->open_date) + (24*3600*30));

	Log::info('expire  '. $expire);
	Log::info('close date '. Input::get('close_date'));

	if(Input::get('close_date') == null)
		$job->close_date = $expire;
	else
		{	$close_date = date_format(date_create(Input::get('close_date')), 'Y-m-d');
	$job->close_date =  $close_date <= $expire ? $close_date : $expire;
}		
$save_status = app('jobready365\Http\Controllers\JobController')->save_data($job);

}catch (\Exception $e){
	Log::info('error in save job api: '.$e);
	return Response::json(array(
		'error' => true, 'result' => 'Fail to Save', 'status_code' => 210
		));
}
Log::info('save status: '.$save_status);
if ($save_status == 1){
	Log::info('save success: '.$save_status);
	return Response::json(array(
		'error' => false, 'result' => 'Success', 'status_code' => 200
		));
}
else{	Log::info('error save status: '.$save_status);
return Response::json(array(
	'error' => true, 'result' => 'Fail to Save', 'status_code' => 210
	));
}
}

public function updateJob_api($id)
{	Log::info('update job api: ');
try{
	$job = job::findOrFail($id);
	$job->is_active = $job->is_active;

	if(Input::get('open_date') != null && Input::get('close_date') != null){
		$open_date = date_format(date_create(Input::get('open_date')), 'Y-m-d');
		$close_date = date_format(date_create(Input::get('close_date')), 'Y-m-d');
		if($open_date > $close_date) {
			return Response::json(array(
				'error' => false, 'result' => 'Open Date is greater than Close Date !!!', 'status_code' => 200
				));
		}
	}

	$job->open_date = Input::get('open_date')!=null ? Input::get('open_date') : date("Y-m-d");
	$expire = date('Y-m-d',strtotime($job->open_date) + (24*3600*30));
	if(Input::get('close_date') == null)
		$job->close_date = $expire;
	else
		$job->close_date =  Input::get('close_date') <= $expire ? Input::get('close_date') : $expire;
	$save_status = app('jobready365\Http\Controllers\JobController')->save_data($job);

}catch (\Exception $e){
	Log::info('error is update job api: '.$e);
	return Response::json(array(
		'error' => true, 'result' => 'Fail to Update Job', 'status_code' => 210));
}
if ($save_status == 1)
	return Response::json(array(
		'error' => false, 'result' => 'Success', 'status_code' => 200
		));
else
	return Response::json(array(
		'error' => true, 'result' => 'Fail to Update Job', 'status_code' => 210
		));
}

public function deleteJobbyId_api($id)
{
	try{
		$ret = app('jobready365\Http\Controllers\JobController')->deleteJobbyId($id,0);
		if($ret == 1) {    $return_code = 200; $msg = "Successfully Deleted!";  }
		else {   $return_code = 210; $msg = "Delete Fail!";  }

		return Response::json(array(
			'error' => false,
			'result' => $msg,
			'status_code' => $return_code
			));
	}catch (\Exception $e){
		Log::info('error in get job info: '.$e);
		return Response::json(array(
			'error' => true,
			'result' => 'error in get job info',
			'status_code' => 210
			));
	}
}

public function getJobbyId_api($id)
{
	try{
		$job = app('jobready365\Http\Controllers\JobController')->getJobbyId($id,0);
		if(sizeof($job) > 0)   {
			$return_code = 200; 
			switch($job[0]->min_salary){
				case 0: 
				$salary_range = $job[0]->max_salary == 0 ?  0 : 1; 
				$salary_type = $job[0]->max_salary == 0 ?  'Negotiate' : 'less than 100000 Ks'; 
				break;
				case 100000: $salary_range = 2; $salary_type = '100000 ~ 300000 Ks';  break;
				case 300000: $salary_range = 3; $salary_type = '300000 ~ 500000 Ks';  break;
				case 500000: $salary_range = 4; $salary_type = '500000 ~ 1000000 Ks';  break;
				case 1000000: $salary_range = 5; $salary_type = 'greater than 1000000 Ks';  break;	            	
			}
			$job[0]->salary_range = $salary_range;
			$job[0]->salary_type= $salary_type;

			$msg = $job;
			Log::info('msg: '.$msg);
		}
		else {   $return_code = 210; $msg = "No Record Found!";  }

		return Response::json(array(
			'error' => false,
			'job_info' => $msg,
			'status_code' => $return_code
			));
	}catch (\Exception $e){
		Log::info('error in get job info: '.$e);
		return Response::json(array(
			'error' => true,
			'result' => 'error in get job info',
			'status_code' => 210
			));
	}

}


public function getAllJobbyEmployerId_api($eid)
{
	try{
		$job = app('jobready365\Http\Controllers\JobController')->getAllJobbyEmployerId($eid,0);
		if(sizeof($job) > 0)   {$return_code = 200; 
			
			foreach($job as $j)
			{
				switch($j->min_salary){
					case 0: $salary_range = $j->max_salary == 0?  0 : 1; break;
					case 100000: $salary_range = 2; break;
					case 300000: $salary_range = 3; break;
					case 500000: $salary_range = 4; break;
					case 1000000: $salary_range = 5; break;

				}
				$j->salary_range = $salary_range;
			}
			
			$msg = $job;}
			else {   $return_code = 210; $msg = "No Record Found!";  }
			
			return Response::json(array(
				'error' => false,
				'job' => $msg,
				'status_code' => $return_code
				));
		}catch (\Exception $e){
			Log::info('error in get candidate count: '.$e);
			return Response::json(array(
				'error' => true,
				'result' => 'error in get job',
				'status_code' => 210
				));
		}
		
	}
	
	public function getCandidateCount_api($cid)
	{
		try{
			$job = app('jobready365\Http\Controllers\HomeController')->getCandidateCount(0, $cid);
			if($job != null && sizeof($job))   {$return_code = 200; $val = $job;}
			else {   $return_code = 210; $val = "No Record Found!";  }
		}catch (\Exception $e){
			Log::info('error in get candidate count: '.$e);
			return Response::json(array(
				'error' => true,
				'result' => 'error in get candidate count',
				'status_code' => 210
				));
		}
		return Response::json(array(
			'error' => false,
			'candidate' => $val,
			'status_code' => $return_code
			));
	}
	
	public function getCandidateList_api($jid)
	{
		try{
			$company = app('jobready365\Http\Controllers\CandidateController')->getCompanybyJob($jid,0);
			if($company!= null && sizeof($company)) {
				$candidate = app('jobready365\Http\Controllers\CandidateController')->getCandidateInfo($jid,0);
				if($candidate!= null && sizeof($candidate))   { $company[0]->candidate=$candidate; $return_code = 200; $val = $company;}
				else {   $return_code = 210; $val= "No Candidate Info Found!";  }
			}
			else{
				$return_code = 210; $val= "No Candidate Info Found!";
			}
		}catch (\Exception $e){
			Log::info('error in get candidate list: '.$e);
			return Response::json(array(
				'error' => true,
				'result' => 'error in get candidate list',
				'status_code' => 210
				));
		}
		return Response::json(array(
			'error' => false,
			'candidate_list' => $val,
			'status_code' => $return_code
			));
	}
	
	
	public function getShortistedCount_api($cid)
	{
		try{
			$job = app('jobready365\Http\Controllers\HomeController')->getShortlistedCount(0, $cid);
			if($job != null && sizeof($job))   {$return_code = 200; $val = $job;}
			else {   $return_code = 210; $val = "No Record Found!";  }
		}catch (\Exception $e){
			Log::info('error in get candidate count: '.$e);
			return Response::json(array(
				'error' => true,
				'result' => 'error in get candidate count',
				'status_code' => 210
				));
		}
		return Response::json(array(
			'error' => false,
			'candidate' => $val,
			'status_code' => $return_code
			));
	}
	
	public function getShortlisted_api($jid)
	{
		try{
			$company = app('jobready365\Http\Controllers\CandidateController')->getCompanybyJob($jid,0);
			if($company!= null && sizeof($company)) {
				$candidate = app('jobready365\Http\Controllers\CandidateController')->shortlisted($jid,0);
				if($candidate!= null && sizeof($candidate))   { $company[0]->candidate=$candidate; $return_code = 200; $val = $company;}
				else {   $return_code = 210; $val= "No Candidate Info Found!";  }
			}
			else{
				$return_code = 210; $val= "No Candidate Info Found!";
			}
		}catch (\Exception $e){
			Log::info('error in get candidate list: '.$e);
			return Response::json(array(
				'error' => true,
				'result' => 'error in get candidate list',
				'status_code' => 210
				));
		}
		return Response::json(array(
			'error' => false,
			'candidate_list' => $val,
			'status_code' => $return_code
			));
	}
	
	
	public function showApplicantbyId_api($id)
	{
		try{
			$applicant = app('jobready365\Http\Controllers\ApplicantController')->showApplicantbyId($id,0);
			if($applicant != null && count($applicant) > 0){
				$education =  education::where('applicant_id', $applicant[0]->user_id)->select('*')->get();
				if($education != null && count($education) > 0) $applicant[0]->education = $education;
				
				$skill =  skill::where('user_id', $applicant[0]->user_id)->select('*')->get();
				if($skill != null && count($skill) > 0) $applicant[0]->skill = $skill;
				
				$experience =  experience::where('user_id', $applicant[0]->user_id)->select('*')->get();
				if($experience != null && count($experience) > 0) $applicant[0]->experience = $experience;
				
				$refree =  refree::where('applicant_id', $applicant[0]->user_id)->select('*')->get();
				if($refree != null && count($refree) > 0) $applicant[0]->refree = $refree;
				
				return Response::json(array('error' => false,'applicant' => $applicant,'status_code' => 200));
			}
			else
				return Response::json(array('error' => false,'applicant' => "Not Record Found!",'status_code' => 210));
		}catch (\Exception $e){
			Log::info('error in showApplicantbyId_api '.$e);
			return Response::json(array(
				'error' => true,
				'result' => 'Cannot retrieve Applicant Info',
				'status_code' => 210
				));
		}
	}
	
	public function applyjob_api()
	{
		try{
			$application = new application();
			//$application->applicant_id = Auth::user()->id;
			if(app('jobready365\Http\Controllers\ApplicationController')->checkduplicate(Input::get('user_id')) == 0){
				Log::info('apply: ');
				$application->applicant_id = Input::get('user_id');
				$return_val = app('jobready365\Http\Controllers\ApplicationController')->apply_job($application);
				if($return_val != '')
					return Response::json(array(
						'error' => false,
						'result' => 'Success',
						'status_code' => 200
						));
				else 
					return Response::json(array(
						'error' => false,
						'result' => 'Fail to Apply Job',
						'status_code' => 210
						));
			}else{
				Log::info('already apply: ');
				return Response::json(array(
					'error' => false,
					'result' => 'You already applied for this job.',
					'status_code' => 200
					));
			}
		}catch (\Exception $e){
			Log::info('error is save refree api: '.$e);
			return Response::json(array(
				'error' => false,
				'result' => 'Error in Apply Job',
				'status_code' => 210
				));
		}
		
	}
	
	public function getAllJobHistory_api($uid){
		try{
			$history = app('jobready365\Http\Controllers\ApplicationController')->getAllJobHistory($uid);
			if($history != null && count($history) > 0)
				return Response::json(array(
					'error' => false,
					'result' => $history,
					'status_code' => 200
					));
			else
				return Response::json(array(
					'error' => false,
					'result' => "No History Record",
					'status_code' => 200
					));

		}catch (\Exception $e){
			Log::info('get sjob history api: '.$e);
			return Response::json(array(
				'error' => false,
				'result' => 'Fail to retrieve Job History',
				'status_code' => 210
				));
		}
	}
	
	public function getAllJobOffer_api($uid){
		try{
			$history = app('jobready365\Http\Controllers\ApplicationController')->getAllJobOffer($uid);
			if($history != null && count($history) > 0)
				return Response::json(array(
					'error' => false,
					'result' => $history,
					'status_code' => 200
					));
			else
				return Response::json(array(
					'error' => false,
					'result' => "No Appointment Record",
					'status_code' => 200
					));

		}catch (\Exception $e){
			Log::info('get sjob history api: '.$e);
			return Response::json(array(
				'error' => false,
				'result' => 'Fail to retrieve Job Appointment ',
				'status_code' => 210
				));
		}
	}
	
	public function getFilterJob_api($str = '')
	{
		$result = app('jobready365\Http\Controllers\JobController')->getLatestJobbyKeyword($str);
		if($result != null)   {$return_code = 200; $msg = $result;}
		else {   $return_code = 210; $msg = "No Record Found!";  }
		//$return_code = 200; $msg = $result;
		return Response::json(array(
			'error' => false,
			'job' => $msg,
			'status_code' => $return_code
			));
	}
	
	public function advancedFilterJob_api()
	{
		$result = app('jobready365\Http\Controllers\JobController')->advancedFilterJob();
		if($result != null)   {$return_code = 200; $msg = $result;}
		else {   $return_code = 210; $msg = "No Record Found!";  }
		//$return_code = 200; $msg = $result;
		return Response::json(array(
			'error' => false,
			'job' => $msg,
			'status_code' => $return_code
			));
	}
	
	public function insertCandidate_api()
	{
		$ret = app('jobready365\Http\Controllers\CandidateController')->save_data();
		if($ret == 1)
			return Response::json(array(
				'error' => false,
				'result' => 'Success',
				'status_code' => 200
				));
		else
			return Response::json(array(
				'error' => true,
				'result' => 'Fail to contact applicant.',
				'status_code' => 210
				));
	}
	
	public function shortlisted_api($uid)
	{
		$result = app('jobready365\Http\Controllers\CandidateController')->shortlisted($uid);
		if($result != null)   {$return_code = 200; $msg = $result;}
		else {   $return_code = 210; $msg = "No Record Found!";  }
		//$return_code = 200; $msg = $result;
		return Response::json(array(
			'error' => false,
			'job' => $msg,
			'status_code' => $return_code
			));
	}
	
	public function getAppointment_api($aid)
	{
		$result= app('jobready365\Http\Controllers\ApplicationController')->getAllJobOffer($aid);
		if($result != null)   {$return_code = 200; $msg = $result;}
		else {   $return_code = 210; $msg = "No Record Found!";  }
		//$return_code = 200; $msg = $result;
		return Response::json(array(
			'error' => false,
			'job_appointment' => $msg,
			'status_code' => $return_code
			));
	}	
	
	public function getRelatedJob_api($aid)
	{
		$result= app('jobready365\Http\Controllers\JobController')->getRelatedJobfromdb($aid);
		Log::info('related job '. $result);
		if($result != null)   {$return_code = 200; $msg = $result;}
		else {   $return_code = 210; $msg = "No Record Found!";  }
		//$return_code = 200; $msg = $result;
		return Response::json(array(
			'error' => false,
			'related_job' => $msg,
			'status_code' => $return_code
			));
	}
	
	public function upgrade_package_api()
	{
		//$user_id = Input::get('user_id');
		$ret = app('jobready365\Http\Controllers\EmployerTopupController')->upgrade_employer_package();
		Log::info('ret '. $ret );
		$remain_time = ''; $package_type='';
		if($ret== 'Gold')  {	$msg = 'You already bought Gold. Please wait Until Expire'; $status_code = 200; }
		else if($ret== 'Platinum')  {	$msg = 'You already bought Platinum. Please wait Until Expire'; $status_code = 200; }
		else if($ret == 'usedkey')	{	$msg = 'The key you entered is already used.'; $status_code = 200; }
		else if($ret == 'invalidkey')	{	$msg = 'You selected package & key are not matched.';  $status_code = 200; }
		else if($ret == 'error')	{	$msg = 'Fail to upgrade package.';  $status_code = 210; }
		else {	$msg = 'Success'; $status_code = 200; $remain_time = $ret->remain_time; $package_type=$ret->package_type;}
		
		return Response::json(array(
			'error' => false,
			'result' => $msg ,
			'status_code' => $status_code,
			'remain_time' => $remain_time,
			'package_type' => $package_type
			));
	}
	
	public function getFilterApplicant_api($keyword){
		//$result= app('jobready365\Http\Controllers\JobController')->getLatestJobbyKeyword($keyword);
		$result= app('jobready365\Http\Controllers\ApplicantController')->getFilterApplicant($keyword);
		if($result != null)   {$return_code = 200; $msg = $result;}
		else {   $return_code = 210; $msg = "No Record Found!";  }
		//$return_code = 200; $msg = $result;
		return Response::json(array(
			'error' => false,
			'filter_applicant' => $msg,
			'status_code' => $return_code
			));
	}
	
	public function getAllApplicantCount_api()
	{
		$job = app('jobready365\Http\Controllers\ApplicantController')->getAllApplicantCount();
		Log::info('job seeker '. $job );
		if($job != null)   {$return_code = 200; $msg = $job[0]->jobseeker_count;}
		else {   $return_code = 210; $msg = "No Record Found!";  }
		
		return Response::json(array(
			'error' => false,
			'jobseeker_count' => $msg,
			'status_code' => $return_code
			));
	}
}