<?php

namespace jobready365\Http\Controllers;

use Request;

use Log;
use Input;
use Redirect;
use Session;
use Auth;
use Ramsey\Uuid\Uuid;
use jobready365\experience;

class ExperienceController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
    	$experience =  $this->getAllExperience(Auth::user()->id);
        return view('applicant.experience', compact('experience'));
    }

    public function store(Request $request)
    {
        try{  
        	$experience = new experience();
        	$experience->id = Uuid::uuid4()->getHex();
        	$experience->user_id = Auth::user()->id;
            	$ret = $this->save_experience_data($experience);
            	if($ret == 'invalid')
            	{
            		Session::flash('duplicate_message', 'Start Date is greater than end date');
            		return redirect()->back();
            	}
            	else if($ret == -1)
            	{
            		Session::flash('duplicate_message', 'Error! Cannot Save.');
            		return redirect()->back();
            	}
    	}catch (\Exception $e){
            	Log::info('save error : '.$e);            	
    	}
    	return Redirect::to(Session::get('lang').'/experience');
    }
    
    public function destroy($id)
    {
    	$ret = $this->deleteExperiencebyId($id);        
        if($ret == 1)           
            return Redirect::to(Session::get('lang').'/experience');
        else 
            return "error in delete";
    }
    
    public function getAllExperience($uid){
    	return experience::where('user_id', $uid)->select('*')->get();  
    }
    
    public function getExperience($id){
    	return experience::findOrFail($id);
    }
    
    public function updateExperiencebyId($id,$encrypt=1){
    	$id = ($encrypt == 1)?decrypt($id):$id;      
    	$ret = 0;
        try
        {        	
        	$experience= experience::findOrFail($id);
        	if($experience!= null)	{
        		$ret = $this->save_experience_data($experience);
        		return $ret;
        	}
        	return $ret;      		
        }
        catch(\Exception  $e)
        {
        	return $ret;
        }
    }
    
    public function deleteExperiencebyId($id, $encrypt=1){
    	$id = $encrypt == 1 ? decrypt($id) : $id;
        $ret = 0;
        try
        {
        	$experience = experience::findOrFail($id);
        	if($experience!= null)	$ret = $experience->delete();
        	return $ret;
        }
        catch(\Exception  $e)
        {
        	return $ret;
        }
    }
    
    public function save_experience_data($experience){
    	try{
    		
    		$experience->organization = Input::get('organization');
    		$experience->rank = Input::get('rank');	
    		Log::info('open date: '.Input::get('start_date')); 
    		Log::info('close date: '.Input::get('end_date')); 
    			
    		if(Input::get('start_date') != null && Input::get('end_date') != null){
    			$open_date = date_format(date_create(Input::get('start_date')), 'Y-m-d');
    			$close_date = date_format(date_create(Input::get('end_date')), 'Y-m-d');
    			if($open_date > $close_date) {
    				return 'invalid';
    			}
    		}
    		Log::info('today '.date("Y-m-d")); 
    		Log::info('selected date '.date_format(date_create(Input::get('end_date')), 'Y-m-d')); 
    		if(Input::get('start_date') != '' || Input::get('start_date') != null) {
    			if(date_format(date_create(Input::get('start_date')), 'Y-m-d') > date("Y-m-d"))
    				return 'over';
    				$experience->start_date = date_format(date_create(Input::get('start_date')), 'Y-m-d');
    				
    		}
    		if(Input::get('end_date') != '' || Input::get('end_date') != null) {
    			if(date_format(date_create(Input::get('end_date')), 'Y-m-d') > date("Y-m-d"))
    				return 'over';
    				$experience->end_date= date_format(date_create(Input::get('end_date')), 'Y-m-d');
    		}
    		else{
    			$experience->end_date = null;
    		}
    		
    		Log::info('save_experience_data: '.$experience); 
    		return $experience->save();
    	}catch (\Exception $e){
    		Log::info('error in save experience: '.$e);   
    		return -1; 		
    		//Session::flash('duplicate_message', 'error in save experience');
    		//return redirect()->back();
    	}
    }
}
