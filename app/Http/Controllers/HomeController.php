<?php

namespace jobready365\Http\Controllers;

use jobready365\country;
use jobready365\company;
use jobready365\city;
use jobready365\township;
use jobready365\job_nature;
use jobready365\job_industry;
use jobready365\User;
use Log;
use Request;
use Response;
use Auth;
use DB;
use Input;
use Yajra\Datatables\Facades\Datatables;
use Config;
use Session;

class HomeController extends Controller
{
	public function index($lang = '')
	{
		$job_nature = app('jobready365\Http\Controllers\JobController')->getJobbyCount('type');
		$category_list = app('jobready365\Http\Controllers\JobController')->getJobbyCount('category');
		$company_list = app('jobready365\Http\Controllers\JobController')->getJobbyCount('company');
		$location = app('jobready365\Http\Controllers\JobController')->getJobbyCount('location');
		
		$job_list= app('jobready365\Http\Controllers\JobController')->getAllActiveJob();
		
		$featured_company = app('jobready365\Http\Controllers\CompanyController')->getAllFeaturedCompany();
		
		$member_count = $this->getMemberCount();
		return view('home',compact('company_list', 'job_list', 'category_list', 'job_nature', 'location','featured_company','member_count'));
	}
	
	public function language(Request $request, $lang)
	{
		//$langs = Config::get('app.locales');
		$langs= array('en', 'mm');
		$default_lang = Config::get('app.locale');
		
		/*if (array_key_exists($lang, $langs)) {
			$request->session()->put('locale', $lang);
		} else {
			$request->session()->put('locale', $default_lang);
		}
		$request->session()->save();
		return response([
				'sessionData' => session()->all()
		]);*/
		if(array_key_exists($lang, $langs)){
			\App::setLocale($lang);   //to change the lang over the entire app
			Config::set('app.locale', $lang);
			$lng = $locale;
		}else{
			$locale = null;  //no prefix for the default lang
		}
		
		/*$languages = array('en', 'mm');  //available languages (without the default language)
		
		$locale= Request::segment(1);
		if($locale== null) $locale = 'en';
		
		if(in_array($lang, $languages)){
			\App::setLocale($lang);   //to change the lang over the entire app
			Config::set('app.locale', $lang);
			$lng = $locale;
		}else{
			$locale = null;  //no prefix for the default lang
		}*/
		Session::set('lang', Config::get('app.locale'));		
	}
	 
	 public function passwordresetform(){
	 	return view('auth.passwords.changepassword');
	 }
	 
	 public function changepassword(Request $request){
	 	//echo Auth::user()->id;
	 	if(Auth::Check())
	 	{
	 		$request_data = $request->All();
	 		$validator = $this->admin_credential_rules($request_data);
	 		if($validator->fails())
	 		{
	 			return response()->json(array('error' => $validator->getMessageBag()->toArray()), 400);
	 		}
	 		else
	 		{
	 			$current_password = Auth::User()->password;
	 			if(Hash::check($request_data['oldpassword'], $current_password))
	 			{
	 				$user_id = Auth::User()->id;
	 				$obj_user = User::find($user_id);
	 				$obj_user->password = Hash::make($request_data['password']);;
	 				$obj_user->save();
	 				return "ok";
	 			}
	 			else
	 			{
	 				$error = array('oldpassword' => 'Please enter correct current password');
	 				return response()->json(array('error' => $error), 400);
	 			}
	 		}
	 	}
	 	else
	 	{
	 		return redirect()->to('/');
	 	}
	 }
	 
	 public function admin_credential_rules(array $data)
	 {
	 	$messages = [
	 			'oldpassword.required' => 'Please enter current password',
	 			'newpassword.required' => 'Please enter password',
	 	];
	 	
	 	$validator = Validator::make($data, [
	 			'oldpassword' => 'required',
	 			'newpassword' => 'required|same:password',
	 			'password_confirmation' => 'required|same:password',
	 	], $messages);
	 	
	 	return $validator;
	 }
	 
	 public function policy(){
	 	return view('policy');
	 }
	 
	 public function terms(){
	 	return view('terms');
	 }
	 
	 public function getMemberCount(){
	 	return User::count();
	 }
	 
	 public function getAllCountry(){
	 	return  country::pluck('country', 'id');
	 }
	 
	 public function getAllCity(){
	 	return  city::pluck('city', 'id');
	 }
	 
	 public function getAllCompanybyEmployer(){ //used in job controller
	 	Log::info('Auth::user()->user_role'.Auth::user()->user_role);
	 	if (Auth::user()->user_role == 1)
	 		return company::where('is_active','1')->orderby('company_name')->pluck('company_name','id');
	 		else
	 			return company::where('user_id',Auth::user()->id)->where('is_active','1')->orderby('company_name')->pluck('company_name','id');
	 }
	 
	 public function getAllTownship(){
	 	return township::pluck('township', 'id');
	 }
	 
	 public function getAllJobIndustry($type = 0){
	 	if($type == 0)
	 		return job_industry::pluck('job_industry', 'id');
	 	else
	 		return job_industry::where("type",$type )->pluck('job_industry','id');
	 }
	 
	 public function getAllTownshipbyCity($city_id = ''){
	 	if($city_id == null)
	 		return township::pluck('township', 'id');
	 		else
	 			return township::where("city_id",$city_id)->pluck('township','id');
	 }
	 
	 public function getAllJobNature(){
	 	return job_nature::pluck('type', 'id');
	 }
	 
	 public function dashboard()
	 {	//Log::info('dashboard: ');
	 	if(Request::wantsJson()){
	 		return Response::json(array(
	 				'error' => false,
	 				'result' => 1,
	 				'status_code' => 200
	 		));
	 	}
	 	else{
	 		return $this->show_dashboard();
	 		//return redirect()->action('HomeController@show_dashboard');
	 	}
	 }
	 
	 public function show_dashboard()
	 {
	 	if(Auth::user()->user_type == 1){
	 		$company_list= $this->getAllCompanybyEmployer();
	 		foreach ($company_list as $key => $value){
	 			$key1 = encrypt($key);
	 			unset($company_list[$key]);
	 			$company_list[$key1]= $value;
	 		}
	 		$employer_info = app('jobready365\Http\Controllers\EmployerController')->getEmployerbyId(encrypt(Auth::user()->id));
	 		return view('employer.employer_dashboard',compact('company_list','employer_info'));
	 	}
	 	else if(Auth::user()->user_type == 2){
	 		$history_list = app('jobready365\Http\Controllers\ApplicationController')->getAllJobHistory(Auth::user()->id);
	 		$job_offer = app('jobready365\Http\Controllers\ApplicationController')->getAllJobOffer(Auth::user()->id);
	 		return view('application.index',compact('history_list','job_offer'));
	 	}
	 	else if(Auth::user()->user_role == 1){
	 		$jobs = app('jobready365\Http\Controllers\JobController')->getAllJob();
	 		return view('job.index', compact('jobs'));
	 	}else if(Auth::user()->user_role == 2){
	 		return view('package_key.index');
	 	}
	 }
	 
	 public function getCandidateCount($encrypt=1,$cid=''){
	 	try{
	 		//if(Input::get('company_id') != ''){
	 		if($encrypt == 1)
	 			$id = decrypt(Input::get('company_id'));
	 		else
	 			$id = $cid;
	 				Log::info('$company_id: '.$id);
	 				
	 				$job = DB::table('jobs as j')
	 				->leftjoin('applications as a','j.id','=','a.job_id')
	 				->whereDate('j.open_date', '<=', date("Y-m-d")) //'2016-11-02'
	 				->whereDate('j.close_date', '>=', date("Y-m-d"))
	 				->where('j.company_id','=',$id)
	 				->where('j.is_deleted','=',0)
	 				->select(DB::raw('count(*) as job_count, j.id,j.job_title,a.job_id'))
	 				->groupBy('j.id','j.job_title','a.job_id')
	 				->orderBy('job_count','desc')
	 				->get();
	 				foreach ($job as $j){
	 					if($j->job_id == '' || $j->job_id == null)
	 					{
	 						$j->job_count = 0;
	 					}
	 				}
	 				Log::info('Candidate Count'.$job);
	 				return $job;
	 				//}
	 				//return null;
	 	}catch (\Exception $e){
	 		Log::info('error reason: '.$e);
	 	}
	 }
	 
	 public function getJobByCandidateCount(){
	 	
	 	$job = $this->getCandidateCount();
	 	
	 	return Datatables::of($job)
	 	->editColumn('result',function($job){
	 		$link_job_summary = '/job/'.encrypt($job->id); //show job detail
	 		$job_title = "<div class='row'><div class='col-md-8'><a target='_blank' href=".$link_job_summary."><h5>".$job->job_title."</h5></a></div>";
	 		
	 		//show candidate list
	 		$link_candidate = '/candidate/'.encrypt($job->id);
	 		$count = $job->job_id == null ? 0 : $job->job_count;
	 		$btn_candidate = "<div class='col-md-4'><span style='margin-left:10px;'><a href='$link_candidate' style='border:none;' target='_blank' >".$count." Candidate(s)</a></span>";
	 		
	 		$link_edit = '/job/'.encrypt($job->id).'/edit';
	 		$btn_edit = "<span style='margin-left:10px;'><a href=".$link_edit." target='_blank'  class='btn btn-primary' style='color:#fff; background-color: green;'><i class='fa fa-edit'> Edit </i></a></span>";
	 		
	 		$id =  "form-delete-record-".$job->id;
	 		$action = "/job/".encrypt($job->id);
	 		$token = csrf_token();
	 		/*$btn_delete = "<form method='post' id='$id' action='$action'>" .$btn_edit ."
	 		 <input name='_method' type='hidden' value='DELETE'>
	 		 <input name='_token' type='hidden' value='$token'>
	 		 <a href='' class='deleteRecord btn btn-primary' style='background-color:red;' data-toggle='modal' data-id='$job->id' data_token='$token'
	 		 data_name='{{ $job->job_title }}' data_destroy_route='{{ route('job.destroy', encrypt($job->id)) }}'>
	 		 <i class='fa fa-remove'></i> Delete</a>
	 		 </form></div>";*/
	 		$btn = "<form method='post' id='$id' action='$action'>
	 		<input name='_method' type='hidden' value='DELETE'>
	 		<input name='_token' type='hidden' value='$token'>
	 		<span style='margin-left:10px;'><a href='' class='deleteRecord' style='color:red;' data-toggle='modal' data-id='$job->id' data_token='$token'
	 		data_name='{{ $job->job_title }}' data_destroy_route='{{ route('job.destroy', encrypt($job->id)) }}'>
	 		<i class='fa fa-remove'></i> Delete</a></span>
	 		</form></div>";
	 		$result = $job_title.$btn_candidate.$btn."</div>";
	 		//Log::info('result_data: '.$result_data);
	 		return $result;})
	 		->make(true);
	 }
	 
		 public function getShortlistedCount($encrypt=1,$cid=''){
	 		try{
	 		
	 		//if(Input::get('company_id') != ''){
		 		if($encrypt == 1)
		 			$id = decrypt(Input::get('company_id'));
		 		else
		 			$id = $cid;
	 				Log::info('$company_id in getShortlistedCount: '.$id);
	 				$job = DB::table('candidates as c')
	 				//->leftjoin('applications as a','c.applicant_id','=','a.applicant_id')
	 				->join('jobs as j','c.job_id','=','j.id')
	 				//->whereDate('j.open_date', '<=', date("Y-m-d")) //'2016-11-02'
	 				//->whereDate('j.close_date', '>=', date("Y-m-d"))
	 				->where('j.company_id','=',$id)
	 				->select(DB::raw('count(*) as job_count, j.id,j.job_title'))
	 				->groupBy('j.id','j.job_title')
	 				->orderBy('job_count','desc')
	 				->get();
	 				
	 				Log::info('Shortlisted Count'.$job);
	 				return $job;
	 				//}
	 				//return null;
	 	}catch (\Exception $e){
	 		Log::info('error in getting shortlisted: '.$e);
	 	}
	 }
	 
	 public function getShortlistedByCandidateCount(){
	 	
	 	$job = $this->getShortlistedCount();
	 	
	 	return Datatables::of($job)
	 	->editColumn('result',function($job){
	 		$link_job_summary = '/job/'.encrypt($job->id); //show job detail
	 		$job_title = "<div class='row'><div class='col-md-8'><a target='_blank' href=".$link_job_summary."><h5>".$job->job_title."</h5></a></div>";
	 		
	 		//show candidate list
	 		//$link_candidate = '/shortlisted/'.encrypt($job->id);
	 		$link_candidate = '/shortlisted/'.encrypt($job->id);
	 		$count =  $job->job_count;
	 		$btn_candidate = "<div class='col-md-4'><span style='margin-left:10px;'><a href='$link_candidate' style='border:none;' target='_blank' >".$count." Candidate(s)</a></span>";
	 		
	 		//$link_edit = '/job/'.encrypt($job->id).'/edit';
	 		//$btn_edit = "<span style='margin-left:10px;'><a href=".$link_edit." target='_blank'  class='btn btn-primary' style='color:#fff; background-color: green;'><i class='fa fa-edit'> Edit </i></a></span>";
	 		
	 		$id =  "form-delete-record-".$job->id;
	 		$action = "/job/".encrypt($job->id);
	 		
	 		$result = $job_title.$btn_candidate."</div>";
	 		//Log::info('result_data: '.$result_data);
	 		return $result;})
	 		->make(true);
	 }

	 public function androidDownload() {
	 	return view('download.android');
	 }

	 public function iosDownload() {
	 	return view('download.ios');
	 }
	 
	 /*
	  public function getAllJobCategory1(){
	  return  job_category::pluck('category', 'id');
	  }
	  */
	 
	 /*
	  public function getAllJobNature1(){
	  return  job_nature::pluck('type', 'id');
	  }
	  */
	 
	 /*
	  public function jobbyTsp($tsp_id)
	  {
	  $company_list = $this->getAllCompany();
	  $job_list= app('jobready365\Http\Controllers\JobController')->getJobbyTsp($tsp_id);
	  return view('home',compact('company_list','job_list'));
	  }
	  */
	 
	 /*
	  public function getAllCompany(){
	  return company::where('is_active','1')->get();
	  }
	  */
	 
	 /*
	  public function getAllCompanyLogo(){
	  return company::get('id','company_name','company_logo');
	  }
	  */
	 
	 /* public function getAllTownshipbyCity(){
	  $city_id = Input::get("city");
	  return DB::table('townships')->select('township', 'id')->where('city_id',$city_id)->orderby('township')->get();
	  }
	  */
}