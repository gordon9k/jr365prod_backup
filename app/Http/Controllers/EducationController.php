<?php

namespace jobready365\Http\Controllers;

use Request;
use Log;
use Input;
use Redirect;
use Session;
use Auth;
use Ramsey\Uuid\Uuid;
use jobready365\applicant;
use jobready365\education;

class EducationController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
    	$education = $this->getAllEducation(Auth::user()->id);
        return view('applicant.education', compact('education'));
    }

    public function store(Request $request)
    {
        try{  
        	$edu = new education();
        	$edu->applicant_id = Auth::user()->id;
        	$edu->id = Uuid::uuid4()->getHex();
            	$ret = $this->save_education_data($edu);
            	Log::info('ret save_education_data: '.$ret ); 
            	if($ret == 1)
            		return Redirect::to(Session::get('lang').'/education');
           	if($ret == 'invalid')
            	{
            		Session::flash('duplicate_message', 'Start Date is greater than end date');
            		return redirect()->back();
           	}
    	}catch (\Exception $e){
            	Log::info('save error : '.$e);            	
    	}
    	
    }
    
    public function destroy($id)
    {
    	$ret = $this->deleteEducationbyId($id);        
        if($ret == 1)           
            return Redirect::to(Session::get('lang').'/education');
        else 
            return "error in delete";
    }
    
    public function getAllEducation($id){
    	return education::where('applicant_id', $id)->select('*')->get();
    }
    
    public function getEducation($id){
    	return education::findOrFail($id);
    }
    
    public function updateEducationbyId($id,$encrypt=1){
    	$id = ($encrypt == 1)?decrypt($id):$id;      
    	$ret = 0;
        try
        {        	
        	$edu = education::findOrFail($id);
        	if($edu != null)	{
        		$ret = $this->save_education_data($edu);
        		return $ret;
        	}        		
        }
        catch(\Exception  $e)
        {
        	return $ret;
        }
    }
    
    public function deleteEducationbyId($id,$encrypt=1)
    {	
    	$id = ($encrypt == 1)?decrypt($id):$id;      
    	$ret = 0;
        try
        {        	
        	$edu = education::findOrFail($id);
        	if($edu != null)	$ret = $edu->delete();
        	return $ret;
        }
        catch(\Exception  $e)
        {
        	return $ret;
        }
    }
    
    public function save_education_data($edu){
    	try{
    		//$edu->id = Uuid::uuid4()->getHex();
    		$edu->university = Input::get('university');
    		$edu->degree = Input::get('degree');
    		Log::info('save_education_data 1: '.$edu); 
    		if(Input::get('sdate') != null && Input::get('edate') != null){
    			$open_date = date_format(date_create(Input::get('sdate')), 'Y-m-d');
    			$close_date = date_format(date_create(Input::get('edate')), 'Y-m-d');
    			Log::info('open: '.$open_date); 
    			Log::info('close: '.$close_date); 
    			if($open_date > $close_date) {
    				return 'invalid';
    			}
    		}
    		
    		if(Input::get('sdate') != '' || Input::get('sdate') != null) {
    			if(date_format(date_create(Input::get('sdate')), 'Y-m-d') > date("Y-m-d"))
    				return 'over';
    			$edu->start_date = date_format(date_create(Input::get('sdate')), 'Y-m-d');
    			
    		}
    		if(Input::get('edate') != '' || Input::get('edate') != null) {
    			if(date_format(date_create(Input::get('edate')), 'Y-m-d') > date("Y-m-d"))
    				return 'over';
    			$edu->end_date= date_format(date_create(Input::get('edate')), 'Y-m-d');
    		}
    		Log::info('save_education_data: '.$edu); 
    		
    		return $edu->save();
    	}catch (\Exception $e){
    		Log::info('error in save education: '.$e);    		
    		Session::flash('duplicate_message', 'error in save education');
    		return redirect()->back();
    	}
    }
    
   /* public function save_education_data(){    
        $json  = Input::get('hdnEdu');
       Log::info($json);
        $eduArr = array();

        if(!is_array($json)){
            $edu = json_decode($json,true);
            foreach($edu as $item) { //foreach element in $arr
                $item_arr = array('id'=>Uuid::uuid4()->getHex(),'applicant_id'=>Auth::user()->id,'university'=>$item['university'],'degree'=>$item['degree'],'year'=>$item['year']);
                array_push($eduArr,$item_arr);
            }
        }
        else{
            foreach($json as $item) { //foreach element in $arr
                $item_arr = array('id'=>Uuid::uuid4()->getHex(),'applicant_id'=>Input::get('user_id'),'university'=>$item['university'],'degree'=>$item['degree'],'year'=>$item['year']);
                array_push($eduArr,$item_arr);
            }
        }
        Log::info($eduArr); 
        education::insert($eduArr); // Eloquent
        //DB::table('education')->insert($eduArr);         
    }*/

    /*  public function getEducationbyApplicantID(){
     $education =  education::where('applicant_id', Auth::user()->id)->select('*')->get();
     return Datatables::of($education)
     ->make(true);
     }
     */
}
