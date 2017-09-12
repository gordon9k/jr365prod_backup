<?php

namespace jobready365\Http\Controllers;

use Illuminate\Http\Request;
use jobready365\job;
use Log;
use Validator;
use Input;
use Redirect;
use Session;
use Auth;
use Ramsey\Uuid\Uuid;
use DB;
use Yajra\Datatables\Facades\Datatables;

class JobController extends Controller
{
    public function __construct()
    {
    }
    
    public function index()
    {   
    	if(Auth::user()->user_role == 1 || Auth::user()->user_role == 2){
            $jobs = $this->getAllJob(); 
            return view('job.index', compact('jobs'));
        }
        else if(Auth::user()->user_type == 1){ //employer
            $jobs = $this->getAllJobbyEmployerId(Auth::user()->id,0);
            Log::info('job by employer: '.$jobs);
            return view('job.index', compact('jobs'));
        }
    }

    public function create()
    {	Log::info('create job: ');
        $country= app('jobready365\Http\Controllers\HomeController')->getAllCountry();
        $city = app('jobready365\Http\Controllers\HomeController')->getAllCity();
        $job_nature = app('jobready365\Http\Controllers\HomeController')->getAllJobNature();
        $job_category = app('jobready365\Http\Controllers\JobCategoryController')->getAllJobCategory();
    	$company=app('jobready365\Http\Controllers\HomeController')->getAllCompanybyEmployer();
        $township = app('jobready365\Http\Controllers\HomeController')->getAllTownship();
        Log::info('create job: '.$township );
        return view("job.create",compact('city','country','job_nature','job_category','company','township'));
    }

    public function store(Request $request)
    {                
        try{            
            $validator=$this->validate_data();          
            if ($validator->fails()) {
                return Redirect::to('job/create')
                    ->withErrors($validator)
                    ->withInput(Input::except('name'));
            } else {
                $job = new job;
                $job->id = Uuid::uuid4()->getHex();
                
                if (Auth::user()->user_role == 1){   //super admin             	
                	$emp = app('jobready365\Http\Controllers\CompanyController')->getCompanybyId(encrypt(Input::get('company_id')));
                	$job->employer_id = $emp[0]->user_id;
                	$job->is_active = 1;
                }
                else if(Auth::user()->user_type == 1) //employer
                {
                	$job->employer_id = Auth::user()->id; 
                	$job->is_active = 0;
                }
                
                if(Input::get('open_date') != null && Input::get('close_date') != null){
                	$open_date = date_format(date_create(Input::get('open_date')), 'Y-m-d');
    			$close_date = date_format(date_create(Input::get('close_date')), 'Y-m-d');
    			if($open_date > $close_date) {
    				Session::flash('duplicate_message', 'Open Date is greater than Close Date');
                		return redirect()->back();
                	}
                }
                
               	$job->open_date = Input::get('open_date')!=null ? Input::get('open_date') : date("Y-m-d");//}
                $expire = date('Y-m-d',strtotime($job->open_date) + (24*3600*30));
                	
                if(Input::get('close_date') == null)
                	$job->close_date =$expire ;
                else
                	$job->close_date =  Input::get('close_date') <= $expire ? Input::get('close_date') : $expire;
                
                $this->save_data($job);
            }
        }catch (\Exception $e){
                Log::info('duplicate job: '.$e);
                Session::flash('duplicate_message', 'Duplicate! Already Exist');
                return redirect()->back();
        }
        return Redirect::to(Session::get('lang').'/job');
    }

    public function show($id)
    {	
        $job = array();
    	$job = $this->getJobbyId($id);
    	if($job != null)
    		return view('application.show', compact('job'));
    	else
    		return view('application.show', compact('job'));
    }
    
    public function edit($id)
    {
        $country= app('jobready365\Http\Controllers\HomeController')->getAllCountry();
        $city = app('jobready365\Http\Controllers\HomeController')->getAllCity();
        $job_nature = app('jobready365\Http\Controllers\HomeController')->getAllJobNature();
        $job_category = app('jobready365\Http\Controllers\JobCategoryController')->getAllJobCategory(); 
        $company=app('jobready365\Http\Controllers\HomeController')->getAllCompanybyEmployer();
        $township = app('jobready365\Http\Controllers\HomeController')->getAllTownship();
        $job = $this->getJobbyId($id);  
        return view('job.edit', compact('job','city','country','job_nature','job_category','company','township'));
    }

    public function update(Request $request, $id)
    {
        try{
                $validator=$this->validate_data();
                if ($validator->fails()) {
                    return Redirect::to('job/'.encrypt($id).'/edit')
                    ->withErrors($validator)
                    ->withInput(Input::except('name'));
                } else 
                {
                	$job = job::findOrFail(decrypt($id));  
                	if (Auth::user()->user_role == 1){   //super admin
                		$emp = app('jobready365\Http\Controllers\CompanyController')->getCompanybyId(encrypt(Input::get('company_id')));
                		$job->employer_id = $emp[0]->user_id;
                		$job->is_active = 1;
                	}
                	else if(Auth::user()->user_type == 1) //employer
                	{
                		$job->employer_id = Auth::user()->id;
                		//$job->is_active = 0;
                	}
                	
                	if(Input::get('open_date') != null && Input::get('close_date') != null){
	                	$open_date = date_format(date_create(Input::get('open_date')), 'Y-m-d');
	    			$close_date = date_format(date_create(Input::get('close_date')), 'Y-m-d');
	    			if($open_date > $close_date) {
	    				Session::flash('duplicate_message', 'Open Date is greater than Close Date !!!');
	                		return redirect()->back();
	                	}
	                }
	                
                	$job->open_date = Input::get('open_date')!=null ? Input::get('open_date') : date("Y-m-d");                	
                	$expire = date('Y-m-d',strtotime($job->open_date) + (24*3600*30));
                		
                	if(Input::get('close_date') == null)
                		$job->close_date =$expire ;
                	else
                		$job->close_date =  Input::get('close_date') <= $expire ? Input::get('close_date') : $expire;
                	
                		
                    	$this->save_data($job);
                }
            
        }catch (\Exception $e){
            Log::info('error in job save: '.$e);            
            Session::flash('save error', 'error in job save');
            return redirect()->back();                
        }
        //return Redirect::to(Session::get('lang').'/dashboard');
        return Redirect::to(Session::get('lang').'/job');
    }

    public function destroy($id)
    {	Log::info('update job status: '.$id);
    	$ret = $this->deleteJobbyId($id);        
        if($ret == 1)           
            return Redirect::to(Session::get('lang').'/job');
        else 
            return "error in delete";
    }

    public function job_activate()
    {
    	try{
    		$job = job::findOrFail(Input::get('hid'));
    		$job->is_active = 1;
    		$job->save();
    	}catch (\Exception $e){
    		Log::info('error update job status: '.$e);
    		return redirect()->back();
    	}
    	return Redirect::to(Session::get('lang').'/job');
    }
    
    // common function //
    public function getAllCompanyJobs($cid){  //show job by company    
        try{           
                $com_job =  DB::table('jobs as j')
                ->join('companies as c', 'j.company_id', '=', 'c.id')
                ->join('job_natures as jn', 'j.job_nature_id', '=', 'jn.id')
                ->where('j.company_id',decrypt($cid))
                ->whereDate('j.close_date', '>=', date("Y-m-d")) //'2016-11-02'
                ->select('j.*','c.company_logo','c.description as com_des','jn.type')->get();
                
                return view('job', compact('com_job'));
                
        }catch (\Exception $e){
            Log::info('error reason: '.$e);
        }
    }
    
    public function getAllJob(){  //show all valid job for frontend
        try{
            /*return DB::table('jobs as j')
            ->join('job_categories as jc', 'j.job_category_id', '=', 'jc.id')
            ->join('job_natures as jn','j.job_nature_id','=','jn.id')
            ->join('townships as t','j.township_id','=','t.id')
            ->whereDate('j.close_date', '>=', date("Y-m-d")) //'2016-11-02'
            ->select('j.id','j.job_title','j.company_name','j.township_id','t.township','jc.category','jn.type')
            ->orderBy('j.created_at','desc')->get(); */
            return DB::table('jobs as j')
            ->join('townships as t','j.township_id','=','t.id')
            ->join('companies as com','j.company_id','=','com.id')
            ->join('cities as c','j.city_id','=','c.id')
            ->where('j.open_date', '<=', date("Y-m-d")) //'2016-11-02'
            ->where('j.close_date', '>=', date("Y-m-d")) //'2016-11-02'
            //->where('j.is_active', '=', 1) 
            ->where('j.is_deleted', '=', 0) 
            ->select('j.*','t.township','c.city','com.company_name','com.company_logo')
            ->orderBy('j.updated_at','desc')->get();            
        }catch (\Exception $e){
            Log::info('error reason: '.$e);
        }
    }
    
    public function getAllActiveJob(){  //show all valid job for frontend
        try{
            /*return DB::table('jobs as j')
            ->join('job_categories as jc', 'j.job_category_id', '=', 'jc.id')
            ->join('job_natures as jn','j.job_nature_id','=','jn.id')
            ->join('townships as t','j.township_id','=','t.id')
            ->whereDate('j.close_date', '>=', date("Y-m-d")) //'2016-11-02'
            ->select('j.id','j.job_title','j.company_name','j.township_id','t.township','jc.category','jn.type')
            ->orderBy('j.created_at','desc')->get(); */
            return DB::table('jobs as j')
            ->join('townships as t','j.township_id','=','t.id')
            ->join('companies as com','j.company_id','=','com.id')
            ->join('cities as c','j.city_id','=','c.id')
            ->where('j.open_date', '<=', date("Y-m-d")) //'2016-11-02'
            ->where('j.close_date', '>=', date("Y-m-d")) //'2016-11-02'
            ->where('j.is_active', '=', 1) 
            ->where('j.is_deleted', '=', 0) 
            ->select('j.*','t.township','c.city','com.company_name','com.company_logo')
            ->orderBy('j.updated_at','desc')->get();            
        }catch (\Exception $e){
            Log::info('error reason: '.$e);
        }
    }
    
    public function getAllJobCount(){  //show all valid job for frontend
        try{
            /*return DB::table('jobs as j')
            ->join('job_categories as jc', 'j.job_category_id', '=', 'jc.id')
            ->join('job_natures as jn','j.job_nature_id','=','jn.id')
            ->join('townships as t','j.township_id','=','t.id')
            ->whereDate('j.close_date', '>=', date("Y-m-d")) //'2016-11-02'
            ->select('j.id','j.job_title','j.company_name','j.township_id','t.township','jc.category','jn.type')
            ->orderBy('j.created_at','desc')->get(); */
            return DB::table('jobs as j')
            ->join('townships as t','j.township_id','=','t.id')
            ->join('companies as com','j.company_id','=','com.id')
            ->join('cities as c','j.city_id','=','c.id')
            ->where('j.open_date', '<=', date("Y-m-d")) //'2016-11-02'
            ->where('j.close_date', '>=', date("Y-m-d")) //'2016-11-02'
            ->where('j.is_active', '=', 1) 
            ->where('j.is_deleted', '=', 0) 
            ->select(DB::raw('count(*) as job_count'))
            ->get();            
        }catch (\Exception $e){
            Log::info('error reason: '.$e);
        }
    }
    
    public function getJobbyTsp($tsp_id){
        return DB::table('jobs as j')
            ->join('job_categories as jc', 'j.job_category_id', '=', 'jc.id')
            ->join('job_natures as jn','j.job_nature_id','=','jn.id')
            ->join('townships as t','j.township_id','=','t.id')
            ->where('j.township_id',decrypr($tsp_id))
            ->whereDate('j.close_date', '>=', date("Y-m-d")) //'2016-11-02'
            ->select('j.id','j.job_title','j.company_name','j.township_id','t.township','jc.category','jn.type')
            ->orderBy('j.created_at','desc')->get(); 
    }    
    /*
    public function getAllJob4JobSeeker(){  //show all valid job for frontend
        try{
            Log::info('getAllJob4JobSeeker');
            $job = $this->getAllJob();  
            return Datatables::of($job)
            ->editColumn('job_title',function($job){
                switch($job->job_nature_id){
                    case('2'):  $type = '<label class="btn-floating orange"> <center>P</center> </label> <b><a href="{{ URL::route( \'application.show\', array($id)) }}">'.$job->job_title.'</a></b>';
                    break;
                    case('3'):  $type = '<label class="btn-floating light-blue"> <center>F</center> </label> <b><a href="{{ URL::route( \'application.show\', array($id)) }}">'.$job->job_title.'</a><b>';
                    break;
                    case('4'):  $type = '<label class="btn-floating grey"> <center>C</center> </label> <b><a href="{{ URL::route( \'application.show\', array($id)) }}">'.$job->job_title.'</a><b>';
                    break;
                    case('5'):  $type = '<label class="btn-floating red"> <center>T</center> </label> <b><a href="{{ URL::route( \'application.show\', array($id)) }}">'.$job->job_title.'</a><b>';
                    break;
                    default:    $type = '<label class="btn-floating green"> <center>FT</center> </label> <b><a href="{{ URL::route( \'application.show\', array($id)) }}">'.$job->job_title.'</a><b>';
                }
                return $type;})->make(true);
        }catch (\Exception $e){
            Log::info('error reason: '.$e);
        }
    }
    */
    public function getLatestJob(){ //Home Page
        try{
            $salary_range = Input::get("salary_range");            
            $type = Input::get("type");
            $category = Input::get("category");
            //$city = Input::get("city");
            $company = Input::get("company");
            $township = Input::get("township");
            
            $data = array(    
                'salary_range'=>$salary_range,          
                'type' => $type,
                'category' => $category,
                //'city' => $city,
                'township' => $township,
                'company' => $company,
            );
            $job = $this->getAllJobbySearch($data);   
            Log::info('getLatestJob'.$job);
            return Datatables::of($job)
            ->editColumn('result',function($job){
            	$link_url = Session::get('lang').'/job/'.encrypt($job->id);
                $result_data = "<a href=".$link_url." class='applied'><div class='row'><div class='col-md-2 hidden-sm hidden-xs'>";
                
                $img_url = $job->company_logo == '' ? "/uploads/company_logo/logo.jpg" : "/uploads/company_logo/".$job->company_logo; 
                $logo = "<img src='".$img_url."' width='80' height='80' style='border:2px solid #999999; border-radius:5px;'><br>".$job->close_date."</div>" ;
               
                $link = "<div class='col-md-8 col-sm-12 job-title'><h5>".$job->job_title."</h5><p>".$job->company_name."</p>";
                $location = "<p class='job-location'>".$job->township." - ".$job->city."</p></div>";
               // $location = "<div class='col-lg-3 col-md-3 col-sm-5 col-xs-12 job-location'><p><strong>".$job->township." - ".$job->city."</strong><br>".$job->close_date."</p></div>";
                
                $type = "<div class='col-md-2 hidden-sm hidden-xs job-type text-center'>"; 
                switch($job->job_nature_id){
                        case('2'):  $type .= "<p class='badge part-time'>Part Time</p>";
                        break;
                        case('3'):  $type .= "<p class='badge freelance'>Freelance</p>";
                        break;
                        case('4'):  $type .= "<p class='badge internship'>Contract</p>";
                        break;
                        case('5'):  $type .= "<p class='badge temporary'>Temporary</p>";
                        break;
                        default:    $type .= "<p class='badge full-time'>Full Time</p>";
                    }
                $type .= "</div>";    
                $result_data .= $logo.$link.$location.$type."</div></a>";
                //Log::info('result_data: '.$result_data);
                return $result_data;})           
            ->make(true);
        }catch (\Exception $e){
            Log::info('error reason: '.$e);
        }
    }
    
    public function getAllJobbySearch($data){ 
        try{
            $whereData = array();
            $min_salary = -1; $max_salary = -1;
            //echo $data['salary_range'];
            switch($data['salary_range']){
                case 0: $min_salary = 0;  $max_salary = 0; break;
                case 1: $min_salary = 0;  $max_salary = 100000; break;
                case 2: $min_salary = 100000;  $max_salary = 300000; break;
                case 3: $min_salary = 300000;  $max_salary = 500000; break;
                case 4: $min_salary = 500000;  $max_salary = 1000000; break;
                case 5: $min_salary = 1000000; $max_salary = 1000000; break;
            }

            if($min_salary != -1)  array_push($whereData, array('j.min_salary','=',$min_salary));
            if($max_salary != -1)  array_push($whereData, array('j.max_salary','=',$max_salary));
            if($data['type'] != 0)  array_push($whereData, array('j.job_nature_id',$data['type'])); 
            if($data['category'] != 0)  array_push($whereData,array('j.job_category_id',$data['category']));  
            //if($data['city'] != 0) array_push($whereData, array('j.city_id',$data['city']));
            if($data['township'] != 0)  array_push($whereData,array('j.township_id',$data['township']));
            if($data['company'] != "0") array_push($whereData,array('j.company_id',$data['company']));
            Log::info($data['salary_range'] .'/'.$data['type']. '/'.$data['category'].'/'.$data['township'].'/'.$data['company']);
            //Log::info($whereData);
            
            return DB::table('jobs as j')
            ->join('townships as t','j.township_id','=','t.id')
            ->join('cities as c','j.city_id','=','c.id')
            ->join('companies as com','j.company_id','=','com.id')
            ->where('j.open_date', '<=', date("Y-m-d")) //'2016-11-02'
            ->where('j.close_date', '>=', date("Y-m-d")) //'2016-11-02'
            ->where('j.is_active', '=', 1) 
            ->where('j.is_deleted', '=', 0) 
            ->where($whereData)
            ->select('j.*','t.township','c.city','com.company_logo','com.company_name')
            ->orderBy('j.updated_at','desc')->get();
                
        }catch (\Exception $e){
            Log::info('error reason: '.$e);
        }
    }
    
    public function getFilterJob(){ //Home Page
            try{
                $keyword= Input::get("keyword");
                $job = $this->getLatestJobbyKeyword($keyword);
                
                return Datatables::of($job)
                ->editColumn('result',function($job){
                    $link_url = Session::get('lang').'/job/'.encrypt($job->id);
                $result_data = "<a href=".$link_url." class='applied'><div class='row'><div class='col-md-2 hidden-sm hidden-xs'>";
                
                $img_url = $job->company_logo == '' ? "/uploads/company_logo/logo.jpg" : "/uploads/company_logo/".$job->company_logo; 
                $logo = "<img src='".$img_url."' width='80' height='80' style='border:2px solid #999999; border-radius:5px;'><br>".$job->close_date."</div>" ;               

                $link = "<div class='col-md-8 col-sm-12 col-xs-12 job-title'><h5>".$job->job_title."</h5><p>".$job->company_name."</p>";
                
                $location = "<p><strong class='job-location'>".$job->township." - ".$job->city."</strong></p></div>";
                
                    $type = "<div class='col-lg-2 col-md-2 hidden-sm hidden-xs job-type text-center'>";
                    switch($job->job_nature_id){
                        case('2'):  $type .= "<p class='badge part-time'>Part Time</p>";
                        break;
                        case('3'):  $type .= "<p class='badge freelance'>Freelance</p>";
                        break;
                        case('4'):  $type .= "<p class='badge internship'>Contract</p>";
                        break;
                        case('5'):  $type .= "<p class='badge temporary'>Temporary</p>";
                        break;
                        default:    $type .= "<p class='badge full-time'>Full Time</p>";
                    }
                    $type .= "</div>";
                    $result_data .= $logo.$link.$location.$type."</div></a>";
                    //Log::info('result_data: '.$result_data);
                    return $result_data;})
                    ->make(true);
            }catch (\Exception $e){
                Log::info('error reason: '.$e);
            }
        }
    
    public function getRecentJob(){    	
    		
    		return DB::table('jobs as j')
    		->join('townships as t','j.township_id','=','t.id')
    		->join('cities as c','j.city_id','=','c.id')
    		->join('companies as com','j.company_id','=','com.id')
    		->where('j.open_date', '<=', date("Y-m-d")) //'2016-11-02'
    		->where('j.close_date', '>=', date("Y-m-d")) //'2016-11-02'
    		->where('j.is_active', '=', 1)
    		->where('j.is_deleted', '=', 0)
    		->select('j.*','t.township','c.city','com.company_logo','com.company_name')
    		->orderBy('j.updated_at','desc')->get();    		    	
    }
    
    public function getLatestJobbyKeyword($data){ //for api
    	Log::info('getLatestJobbyKeyword '.$data);
        try{
            if($data != '')
            	return DB::table('jobs as j')
            	->join('townships as t','j.township_id','=','t.id')
            	->join('cities as c','j.city_id','=','c.id')
            	->join('companies as com','j.company_id','=','com.id')
            	->join('job_categories as jc','j.job_category_id','=','jc.id')
            	->where('j.open_date', '<=', date("Y-m-d")) //'2016-11-02'
            	->where('j.close_date', '>=', date("Y-m-d")) //'2016-11-02'            	
		->where('j.is_active', '=', 1)
            	->where('j.is_deleted', '=', 0)             	
            	->where(function($q) use ($data){
	         	return $q->where('j.job_title', 'like', '%' . $data . '%')
	            	->orwhere('com.company_name', 'like', '%' . $data . '%')
			->orwhere('t.township', 'like', '%' . $data . '%');
	      })         	
            	->select('j.*','t.township','c.city','com.company_logo','com.company_name','jc.category')
            	->orderBy('j.updated_at','desc')->get();
            else
                return DB::table('jobs as j')
                ->join('townships as t','j.township_id','=','t.id')
                ->join('cities as c','j.city_id','=','c.id')
                ->join('companies as com','j.company_id','=','com.id')
                ->join('job_categories as jc','j.job_category_id','=','jc.id')
                ->where('j.open_date', '<=', date("Y-m-d")) //'2016-11-02'
                ->where('j.close_date', '>=', date("Y-m-d")) //'2016-11-02'
                ->where('j.is_active', '=', 1)
                ->where('j.is_deleted', '=', 0)
                ->select('j.*','t.township','c.city','com.company_logo','com.company_name','jc.*')
                ->orderBy('j.updated_at','desc')->get();
        	
        	/*
            $whereData = array();
            if($data['type'] != '') array_push($whereData, array('jn.type',$data['type']));
            if($data['category'] != '') array_push($whereData,array('jc.category',$data['category']));
            if($data['city'] != '') array_push($whereData, array('c.city',$data['city']));
            if($data['township'] != '') array_push($whereData,array('t.township',$data['township']));
            
            //return $whereData;
            return DB::table('jobs as j')
            ->join('townships as t','j.township_id','=','t.id')
            ->join('companies as com','j.company_id','=','com.id')
            ->join('cities as c','j.city_id','=','c.id')
            ->join('job_categories as jc','j.job_category_id','=','jc.id')
            ->join('job_natures as jn','j.job_nature_id','=','jn.id')
            //->where('j.open_date', '<', date("Y-m-d")) //'2016-11-02'
            ->where('j.close_date', '>=', date("Y-m-d")) //'2016-11-02'
            //->where('j.is_active', '=', 1) 
            ->where($whereData)
            ->select('j.*','t.township','c.city','jn.type','jc.category','com.company_logo')
            ->orderBy('j.open_date','desc')->get();*/
             
        }catch (\Exception $e){
            Log::info('error reason: '.$e);
        }
    }
    
    
    public function advancedFilterJob(){ //api - advancedFilterJob
    	try{
    		$salary_range = Input::get("salary_range");
    		$category = Input::get("category");
    		$township = Input::get("township");
    		$keyword= Input::get("keyword");
    		
    		$data = array(
    				'salary_range'=>Input::get("salary_range"),
    				'category' => Input::get("category"),
    				'township' => Input::get("township"),
    				'keyword' => Input::get("keyword")		
    		);
    		
    		$whereData = array();
            	$min_salary = -1; $max_salary = -1;

	            switch($data['salary_range']){
	                case 0: $min_salary = 0;  $max_salary = 0; break;
	                case 1: $min_salary = 0;  $max_salary = 100000; break;
	                case 2: $min_salary = 100000;  $max_salary = 300000; break;
	                case 3: $min_salary = 300000;  $max_salary = 500000; break;
	                case 4: $min_salary = 500000;  $max_salary = 1000000; break;
	                case 5: $min_salary = 1000000; $max_salary = 1000000; break;
	            }

            	if($min_salary != -1)  array_push($whereData, array('j.min_salary','=',$min_salary));
            	if($max_salary != -1)  array_push($whereData, array('j.max_salary','=',$max_salary));
            	if($data['category'] != 0)  array_push($whereData,array('j.job_category_id',$data['category']));  
            	if($data['township'] != 0)  array_push($whereData,array('j.township_id',$data['township']));
            	
            
            return DB::table('jobs as j')
            ->join('townships as t','j.township_id','=','t.id')
            ->join('cities as c','j.city_id','=','c.id')
            ->join('companies as com','j.company_id','=','com.id')
            ->where('j.open_date', '<=', date("Y-m-d")) //'2016-11-02'
            ->where('j.close_date', '>=', date("Y-m-d")) //'2016-11-02'
            ->where('j.is_active', '=', 1) 
            ->where('j.is_deleted', '=', 0) 
            ->where($whereData)
            ->where(function($q) use ($data){
	         	return $q->where('j.job_title', 'like', '%' . $data['keyword']. '%')
	            	->orwhere('com.company_name', 'like', '%' . $data['keyword']. '%');
	      })
            ->select('j.*','t.township','c.city','com.company_logo','com.company_name')
            ->orderBy('j.updated_at','desc')->get();
    		Log::info('advancedFilterJob'.$job);
    		
    		return $job;
    	}catch (\Exception $e){
    		Log::info('error reason: '.$e);
    	}
    }

    public function getAllJobbyEmployerId($eid,$encrypt=1){  //show all job by employer
    	$eid= ($encrypt==1)?decrypt($eid):$eid;
        /*return DB::table('jobs as j')
        ->join('companies as com', 'j.company_id', '=', 'com.id')
        ->join('job_categories as jc', 'j.job_category_id', '=', 'jc.id')
        ->join('job_natures as jn','j.job_nature_id','=','jn.id')
        ->where('employer_id',$eid)
        //->where('j.is_active','1')
        ->select('j.*','jc.category','jn.type','com.company_name')
        ->orderBy('j.updated_at','desc')->get();*/
        return DB::table('jobs as j')
        ->join('townships as t','j.township_id','=','t.id')
        ->join('cities as c','j.city_id','=','c.id')
        ->join('companies as com', 'j.company_id', '=', 'com.id')
        ->join('job_categories as jc', 'j.job_category_id', '=', 'jc.id')
        ->join('job_natures as jn','j.job_nature_id','=','jn.id')
        ->where('employer_id',$eid)
        ->where('j.is_deleted', '=', 0)
        ->select('j.*','jc.category','jn.type','com.company_name','com.company_logo','t.township','c.city')
        ->orderBy('j.updated_at','desc')->get();
    }
	
    public function getJobbyId($id,$encrypt=1){
    	try{
	    	$id= ($encrypt==1)?decrypt($id):$id;
	    	$job = DB::table('jobs as j')
	    	->join('companies as com', 'j.company_id', '=', 'com.id')
	    	->join('job_categories as jc', 'j.job_category_id', '=', 'jc.id')
	    	->join('job_natures as jn','j.job_nature_id','=','jn.id')
	    	->join('townships as tsp', 'j.township_id', '=', 'tsp.id')
	    	->join('cities as c', 'j.city_id', '=', 'c.id')
	    	->where('j.id',$id)
	    	->select('j.*','jc.category','jn.type','tsp.township','c.city','com.company_name','com.company_logo')->get();
	    	Log::info('current job: '.$job);
	    	return $job;
    	}catch(\Exception $e){
    	//	return 0;
    	}
    }
    
    public function deleteJobbyId($id,$encrypt=1) //update is_active = 0
    {	$id= ($encrypt==1)?decrypt($id):$id;
    	$job = job::findOrFail($id);
    	$job->is_deleted = 1;
    	Log::info('delete job: '.$job);
    	return $job->save();
    }
    
    public function getAllJobbyCategoryId($cid){  //show all job by category
        return DB::table('jobs as j')
        ->join('job_categories as jc', 'j.job_category_id', '=', 'jc.id')
        ->join('job_natures as jn','j.job_nature_id','=','jn.id')
        ->where('job_category_id',decrypt($cid))
        ->select('j.*','jc.category','jn.type')
        ->orderBy('j.created_at','desc')->get();
    }   
    

    public function validate_data(){
        $rules = array('job_title' => 'required');        
        return Validator::make(Input::all(), $rules);
    }
    
    public function save_data($job){    
        try{ 
            $job->company_id = Input::get('company_id');
            
           // if(Input::get('same_loc') == true){
	            $company = app('jobready365\Http\Controllers\CompanyController')->getCompanybyId(encrypt($job->company_id));
	            if($company != null){
	            	$job->township_id = $company[0]->township_id;
	            	$job->city_id = $company[0]->city_id;
	            	$job->country_id = $company[0]->country_id;
	            	$job->contact_info = $company[0]->address;
	            }
            /*}
            else {
            	$job->contact_info = Input::get('contact_info');  
            	$job->township_id = Input::get('township');
            	$job->city_id = Input::get('city');
            	$job->country_id = 1;
            }
            */
            $job->job_nature_id = Input::get('job_nature');       
            $job->job_category_id = Input::get('job_category');     
            $job->job_title = Input::get('job_title');
            
            switch(Input::get('salary_range')){
            	case 0: $job->min_salary = 0;  $job->max_salary = 0; break;
            	case 1: $job->min_salary = 0;  $job->max_salary = 100000; break;
            	case 2: $job->min_salary = 100000;  $job->max_salary = 300000; break;
            	case 3: $job->min_salary = 300000;  $job->max_salary = 500000; break;
            	case 4: $job->min_salary = 500000;  $job->max_salary = 1000000; break;
            	case 5: $job->min_salary = 1000000;  $job->max_salary = 10000000; break;
            }
            
            
          /*  Log::info('description : '.Input::get('description'));
            Log::info('qualification: '.Input::get('qualification'));
            Log::info('requirement: '.Input::get('requirement'));*/
            
            /*$job->summary = Input::get('description');
            $job->description = Input::get('qualification');
            $job->requirement = Input::get('requirement');*/
            $job->summary = Input::get('summary');
            $job->description = Input::get('description');
            $job->requirement = Input::get('requirement');
            
           // $job->education = Input::get('education');
            $job->language_skill = Input::get('language_skill');
           // $job->experience = Input::get('experience');
            //$expire = time() + (30 * 24 * 60 *60);
            /*if(Input::get('otoday') == 1){
            	$job->open_date = Input::get('open_date')!=null ? Input::get('open_date') : date("Y-m-d");}
            else{
            	$job->open_date = date("Y-m-d");}
            
            $expire = date('Y-m-d',strtotime($job->open_date) + (24*3600*30));            
            
           	if(Input::get('ctoday') == 1){
           		if(Input::get('close_date') == null)
           			$job->close_date =$expire ;
           		else 
           			$job->close_date =  Input::get('close_date') <= $expire ? Input::get('close_date') : $expire;
           	}
            else{           		
           		$job->close_date = $expire ;
           	}*/
           	$job->graduate = Input::get('graduate') == null ? 0 : Input::get('graduate');  
           	$job->accomodation = Input::get('accomodation') == null ? 0 :Input::get('accomodation');          	
           	$job->single = Input::get('single') == null ? 0 : Input::get('single') ;
           	$job->food_supply = Input::get('food') == null ? 0 : Input::get('food');
           	$job->ferry_supply = Input::get('transportation') == null ? 0 : Input::get('transportation');
           	//$job->training = Input::get('training ');
           	
           	$job->male = Input::get('male') == '' ? 0 : Input::get('male');
           	$job->female = Input::get('female') == '' ? 0 : Input::get('female');
           	$job->unisex = Input::get('unisex') == '' ? 0 : Input::get('unisex');
           	
           	$job->min_age = Input::get('min_age') == '' ? 0 : Input::get('min_age');
           	$job->max_age = Input::get('max_age') == '' ? 0 : Input::get('max_age');
           	
           	Log::info('save job: '.$job);
            	return $job->save();
        }catch(\Exception $e){
            Log::info('save error: '.$e);
            return 0;
        }
    }
    
    	public function getRelatedJob(){	
		$job_list = $this->getRelatedJobfromdb(Auth::user()->id);
		return view('applicant.relatedjob', compact('job_list'));	
	}
	
	public function getRelatedJobfromdb($id){
		$applicant_info = DB::table('applicants as a')
		->where('a.user_id','=',$id)->select('a.*')->get();
		
		$related_field = explode(",",$applicant_info[0]->desired_position);
		//print_r($related_field);
		$where = [];
		$i = 0;
		foreach ($related_field as $related){
			$job_category = DB::table('job_categories as jc')
			->where('category',trim($related))->get();
			if(sizeof($job_category) > 0)	$where[$i]=$job_category[0]->id;
			$i++;
		}
		/*
		 $job_category = DB::table('job_categories as jc')
		 ->whereIn('id', $where)->get();
		 
		 echo sizeof($where);
		 
		 */
		$job_list= DB::table('jobs as j')
		->join('townships as t','j.township_id','=','t.id')
		->join('cities as c','j.city_id','=','c.id')
		->join('companies as com','j.company_id','=','com.id')
		->join('job_categories as jc','j.job_category_id','=','jc.id')
		->where('j.open_date', '<=', date("Y-m-d")) //'2016-11-02'
		->where('j.close_date', '>=', date("Y-m-d")) //'2016-11-02'
		->where('j.is_active', '=', 1)
		->where('j.is_deleted', '=', 0)
		->whereIn('job_category_id', $where)
		->select('j.*','t.township','c.city','com.company_logo','com.company_name','jc.category')
		->orderBy('j.updated_at','desc')->get();
		return $job_list;
		//return view('applicant.relatedjob', compact('job_list'));
		
	}

    public function getJobbyCount($filter){ 
        if($filter == 'type')
            return DB::table('jobs as j')
            ->whereDate('j.open_date', '<=', date("Y-m-d")) //'2016-11-02'
            ->whereDate('j.close_date', '>=', date("Y-m-d"))
            ->where('j.is_active', '=', 1) 
            ->where('j.is_deleted', '=', 0)           
            ->select(DB::raw('count(*) as job_count, job_nature_id'))
            ->groupBy('j.job_nature_id')
            ->orderBy('job_count','desc')->get();
        elseif($filter == 'category') 
            return DB::table('jobs as j')
            ->join('job_categories as jc','j.job_category_id','=','jc.id')
            ->whereDate('j.open_date', '<=', date("Y-m-d")) //'2016-11-02'
            ->whereDate('j.close_date', '>=', date("Y-m-d"))
            ->where('j.is_active', '=', 1) 
            ->where('j.is_deleted', '=', 0)
            ->select(DB::raw('count(*) as job_count, j.job_category_id, jc.category'))
            ->groupBy('j.job_category_id', 'jc.category')
            ->orderBy('job_count','desc')->limit(8)->get();
        elseif($filter == 'company')   
            return DB::table('jobs as j')
            ->join('companies as c','j.company_id','=','c.id')
            ->whereDate('j.open_date', '<=', date("Y-m-d")) //'2016-11-02'
            ->whereDate('j.close_date', '>=', date("Y-m-d"))
            ->where('j.is_active', '=', 1) 
            ->where('j.is_deleted', '=', 0)
            ->select(DB::raw('count(*) as job_count, j.company_id, c.company_name, c.company_logo'))
            ->groupBy('j.company_id', 'c.company_name','c.company_logo')
            ->orderBy('job_count','desc')->limit(8)->get();
        elseif($filter == 'location')   
            return DB::table('jobs as j')
            ->join('townships as t','j.township_id','=','t.id')
            ->whereDate('j.open_date', '<=', date("Y-m-d")) //'2016-11-02'
            ->whereDate('j.close_date', '>=', date("Y-m-d"))
            ->where('j.is_active', '=', 1) 
            ->where('j.is_deleted', '=', 0)
            ->select(DB::raw('count(*) as job_count, j.township_id, t.township'))
            ->groupBy('j.township_id',  't.township')
            ->orderBy('job_count','desc')->limit(8)->get();
    }    
}