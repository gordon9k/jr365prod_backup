<?php

namespace jobready365\Http\Controllers;

use Request;

use Log;
use Validator;
use Input;
use Redirect;
use Session;
use DB;
use Auth;
use Illuminate\Database\Query\Builder;
use Ramsey\Uuid\Uuid;
use jobready365\applicant;
use jobready365\skill;
use Storage;
use File;

class SkillController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
    	$skill =  $this->getAllSkill(Auth::user()->id);    	
        return view('applicant.skill', compact('skill'));
    }

    public function store(Request $request)
    {
        try{  
        	$skill = new skill();
        	$skill->id = Uuid::uuid4()->getHex();
    		$skill->user_id = Auth::user()->id;
            $this->save_skill_data($skill);
    	}catch (\Exception $e){
            	Log::info('save error : '.$e);            	
    	}
    	return Redirect::to(Session::get('lang').'/skill');
    }
    
    public function destroy($id)
    {
    	$ret = $this->deleteSkillbyId($id);        
        if($ret == 1)           
            return Redirect::to(Session::get('lang').'/skill');
        else 
            return "error in delete";
    }
    
    public function getAllSkill($uid){
    	return skill::where('user_id', $uid)->select('*')->get();
    }
    
    public function getSkill($id){
    	return skill::findOrFail($id);
    }
    
    public function updateSkillbyId($id,$encrypt=1){
    	$id = ($encrypt == 1)?decrypt($id):$id;      
    	$ret = 0;
        try
        {        	
        	$skill= skill::findOrFail($id);
        	if($skill!= null)	{
        		$ret = $this->save_skill_data($skill);
        	}        		
        }
        catch(\Exception  $e)
        {
        	return $ret;
        }
    }
    
   public function deleteSkillbyId($id, $encrypt=1){
    
        $id = ($encrypt == 1)?decrypt($id):$id;
        $ret = 0;
        try
        {
        	$skill= skill::findOrFail($id);
        	if($skill!= null)	$ret = $skill->delete();
        	return $ret;
        }
        catch(\Exception  $e)
        {
        	return $ret;
        }
    }
    
    public function save_skill_data($skill){
    	try{
    		
    		$skill->language = Input::get('language');
    		$skill->spoken_level = Input::get('slevel');
    		$skill->written_level = Input::get('wlevel');
    		Log::info('skill'.$skill);   
    		return $skill->save();
    	}catch (\Exception $e){
    		Log::info('error in save skill: '.$e);    		
    		Session::flash('duplicate_message', 'error in save skill');
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
