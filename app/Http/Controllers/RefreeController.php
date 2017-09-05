<?php

namespace jobready365\Http\Controllers;

use Request;
use Log;
use Input;
use Redirect;
use Session;
use Auth;
use Ramsey\Uuid\Uuid;
use jobready365\refree;

class RefreeController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
    	$refree =  $this->getAllRefree(Auth::user()->id);
        return view('applicant.refree', compact('refree'));
    }

    public function store(Request $request)
    {
        try{  
        	$refree = new refree();
        	$refree->applicant_id = Auth::user()->id;
            $this->save_refree_data($refree);
    	}catch (\Exception $e){
            Log::info('save error : '.$e);            	
    	}
    	return Redirect::to(Session::get('lang').'/refree');
    }
    
    public function destroy($id)
    {
    	$ret = $this->deleteRefreebyId($id);        
        if($ret == 1)           
            return Redirect::to(Session::get('lang').'/refree');
        else 
            return "error in delete";
    }
    
    public function getAllRefree($uid){
    	return refree::where('applicant_id', $uid)->select('*')->get();
    }
    
    public function deleteRefreebyId($id, $encrypt=1){    
        $id = $encrypt == 1 ? decrypt($id) : $id;
        $ret = 0;
        try
        {
        	$refree = refree::findOrFail($id);
        	if($refree != null)	$ret = $refree ->delete();
        	return $ret;
        }
        catch(\Exception  $e)
        {
        	return $ret;
        }
    }
    
    public function save_refree_data($refree){
    	try{    		
        	$refree->id = Uuid::uuid4()->getHex();
    		$refree->first_name = Input::get('first_name');
    		$refree->last_name = Input::get('last_name');
    		$refree->organization = Input::get('organization');  
    		$refree->rank = Input::get('rank');
    		$refree->mobile_no = Input::get('mobile_no');
    		$refree->email = Input::get('email');
    		$refree->save();
    	}catch (\Exception $e){
    		Log::info('error in save refree: '.$e);    		
    		Session::flash('duplicate_message', 'error in save refree');
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
