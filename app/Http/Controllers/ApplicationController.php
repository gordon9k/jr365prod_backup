<?php

namespace jobready365\Http\Controllers;

use Illuminate\Http\Request;
use jobready365\application;
use Log;
use Input;
use Redirect;
use Session;
use DB;
use Auth;
use Ramsey\Uuid\Uuid;

class ApplicationController extends Controller
{
    public function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get job application history by job seeker
    	$history_list = $this->getAllJobHistory(Auth::user()->id);
    	$job_offer = $this->getAllJobOffer(Auth::user()->id);
    	return view('application.index',compact('history_list','job_offer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {	
    	
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {   
    	Log::info('store: '.Input::get('hid'));     	
        $msg = '';
        try{
            $application = new application;
            if($this->checkduplicate(Auth::user()->id) == 0){             
                $application->applicant_id = Auth::user()->id;    
                $msg = $this->apply_job($application);
                Session::flash('flash_message', $msg);
            } 
            else{
                Session::flash('duplicate_message', "You already applied for this job.");
            }                   
        }catch (\Exception $e){
            Session::flash('duplicate_message', 'Cannot apply');
            return redirect()->back();
        }
        return redirect()->back();
    }

    public function apply_job($application)
    {   
        //Log::info('get job id: '.Input::get('hid'));   
        Log::info('get count: '.$this->checkduplicate(Input::get('user_id')));       
        $msg = '';
        try{            
            $application->id = Uuid::uuid4()->getHex();                
            $application->job_id = Input::get('hid');
            $application->date_apply = date("Y-m-d");
            Log::info('applicant: '.$application);
            $application->save();
            $msg = "Successfully Apply!";
        }catch (\Exception $e){
            //Session::flash('duplicate_message', 'Cannot apply');
            Log::info('error apply job: '.$e);   
            return $msg;
        }
        // redirect
        //Session::flash('flash_message', $msg);
        return $msg;
    }
    
    public function show($id)
    {	
        $job = app('jobready365\Http\Controllers\JobController')->getJobbyId($id);
        return view("application.show",compact('job'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$ret = $this->deleteApplicationbyId($id);        
        if($ret == 1)           
        {
            Session::flash('flash_message', "Your application is Successfully deleted.");
            return redirect()->back();
        }
        else 
        {
            Session::flash('flash_message', "Your application cannot be deleted.");
            return redirect()->back();
        }        
    }

    //common function
    public function getAllJobHistory($uid){        
        return DB::table('applications as app')
        ->join('jobs as j','app.job_id','=','j.id')
        ->join('companies as c','j.company_id','=','c.id')
        ->join('townships as t','j.township_id','=','t.id')
        ->join('job_categories as jc','j.job_category_id','=','jc.id')
        ->where('applicant_id', $uid)
        ->select('app.*','j.job_title','j.job_nature_id','j.close_date','c.company_name','t.township','jc.category')
        ->orderBy('app.created_at','desc')->get();
    }   
    
    public function getAllJobOffer($id)
    {
    	return DB::table('candidates as c')->where('c.applicant_id',$id)
    	->orderby('c.created_at')
    	->get();
    }
    
    public function checkduplicate($user_id){
    	//Log::info('apply count: '.application::where('applicant_id',$user_id)->where('job_id', Input::get('hid'))->count());
    	Log::info('jobid '.Input::get('hid'));
    	Log::info('user id'.Input::get('user_id'));
        return application::where('applicant_id',$user_id)
        ->where('job_id', Input::get('hid'))->count();        
    }

    public function deleteApplicationbyId($id) //update is_active = 0
    {
    	Log::info('delete job application: '.decrypt($id));        
        $application = application::findOrFail($id);     
        return $application->delete();
    }
        
    /*public function sendCV($attach_file){
    	Log::info('$attach_file: '.$attach_file);
    	$job_id = Input::get('hid');
    	$info =  DB::table('jobs as j')
    		->join('companies as c','j.company_id','=','c.id')    		
    		->where('j.id','=',$job_id)
    		->select('c.email')    		
    		->orderBy('j.open_date','desc')->get();  
    	try{
    		$data = array('mailTo' => $info[0]->email, 'subject' => 'Job Application Letter' ,'attach' => $attach_file);
    		Mail::send('email.welcome', ['data' => $data], function ($message) use ($data) {
    			$message->from('lin.diana6@gmail.com', 'jobready365.com');
    			$message->attach($data['attach']);
    			$message->to($data['mailTo'])->subject($data['subject']);
    		});
    	}catch (\Exception $e){
    		Log::info('exception: '.$e); 
    		Session::flash('flash_message', "Error in Email Sending");
    		return redirect()->back();
    	}
    }*/
}