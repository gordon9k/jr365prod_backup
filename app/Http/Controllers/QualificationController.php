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
use jobready365\qualification;

class QualificationController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
    	$qualification = $this->getAllQualification(Auth::user()->id);
        return view('applicant.qualification', compact('qualification'));
    }

    public function store(Request $request)
    {
        try{  
        	$qua = new qualification();
        	$qua->id = Uuid::uuid4()->getHex();
        	$qua->applicant_id = Auth::user()->id;
        	$ret = $this->save_qualification_data($qua);
        	if($ret == 'invalid')
        	{
        		Session::flash('duplicate_message', 'Start Date is greater than end date');
        		return redirect()->back();
        	}
    	}catch (\Exception $e){
            	Log::info('save error : '.$e);            	
    	}
    	return Redirect::to(Session::get('lang').'/qualification');
    }
    
    public function destroy($id)
    {
    	$ret = $this->deleteQualificationbyId($id);        
        if($ret == 1)           
            return Redirect::to(Session::get('lang').'/qualification');
        else 
            return "error in delete qualification";
    }
    
    public function getAllQualification($id){
    	return qualification::where('applicant_id', $id)->select('*')->get();
    }
    
    public function getQualification($id){
    	return qualification::findOrFail($id);
    }
    
    public function updateQualificationbyId($id,$encrypt=1){
    	$id = ($encrypt == 1)?decrypt($id):$id;      
    	$ret = 0;
        try
        {        	
        	$qua = qualification::findOrFail($id);
        	if($qua != null)	{
        		$ret = $this->save_qualification_data($qua );
        		return $ret;
        	}        		
        }
        catch(\Exception  $e)
        {
        	return $ret;
        }
    }
    
    public function deleteQualificationbyId($id,$encrypt=1)
    {	
    	$id = ($encrypt == 1)?decrypt($id):$id;      
    	$ret = 0;
        try
        {        	
        	$qua = qualification::findOrFail($id);
        	if($qua!= null)	$ret = $qua->delete();
        	return $ret;
        }
        catch(\Exception  $e)
        {
        	return $ret;
        }
    }
    
    public function save_qualification_data($qua){
    	try{
    		$qua->center_name = Input::get('center_name');
    		$qua->course = Input::get('course');
    		
    		if(Input::get('sdate') != null && Input::get('edate') != null){
    			$open_date = date_format(date_create(Input::get('sdate')), 'Y-m-d');
    			$close_date = date_format(date_create(Input::get('edate')), 'Y-m-d');
    			if($open_date > $close_date) {
    				return 'invalid';
    			}
    		}
    		
    		if(Input::get('sdate') != '' || Input::get('sdate') != null) {
    			if(date_format(date_create(Input::get('sdate')), 'Y-m-d') > date("Y-m-d"))
    				return 'over';
    				$qua->start_date = date_format(date_create(Input::get('sdate')), 'Y-m-d');
    				
    		}
    		if(Input::get('edate') != '' || Input::get('edate') != null) {
    			if(date_format(date_create(Input::get('edate')), 'Y-m-d') > date("Y-m-d"))
    				return 'over';
    				$qua->end_date= date_format(date_create(Input::get('edate')), 'Y-m-d');
    		}
    		/*
    		$sdate = date_create(Input::get('sdate'));
    		$edate = date_create(Input::get('edate'));
    		
    		if(Input::get('sdate') != '' || Input::get('sdate') != null) $qua->start_date = date_format($sdate , 'Y-m-d');
    		if(Input::get('edate') != '' || Input::get('edate') != null) $qua->end_date = date_format($edate, 'Y-m-d');
    		if($qua->start_date > $qua->end_date) return 'invalid';
    		*/
    		Log::info('save_qualification_data: '.$qua); 
    		$qua->save();
    	}catch (\Exception $e){
    		Log::info('error in save qualification: '.$e);    		
    		Session::flash('duplicate_message', 'error in save qualification');
    		return redirect()->back();
    	}
    }
    
}
