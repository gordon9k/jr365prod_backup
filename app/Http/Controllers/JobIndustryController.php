<?php

namespace jobready365\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use jobready365\job_industry;
use Log;
use Validator;
use Input;
use Redirect;
use Session;

class JobIndustryController extends Controller
{	
	protected $respose;
	
	public function __construct(Response $response)
	{
		$this->response = $response;	
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $rules = array(
            'name'       => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
        if ($validator->fails()) {
            return Redirect::to('jobcategory')
                ->withErrors($validator)
                ->withInput(Input::except('name'));
        } else {
            try{
            	$job_industry= new job_industry;
            	$job_industry->job_industry = Input::get('name');
            	$job_industry->type= Input::get('ddlBType');
            	Log::info('job industry --- '.$job_industry->job_industry);
            	$job_industry->save();
            }catch (\Exception $e){
                Log::info('error in save industry: '.$e);
                $errorCode = $e->errorInfo[1];
                if($errorCode == 1062){
                    Session::flash('duplicate_message', 'Duplicate Industry! Already Exist');
                    return redirect()->back();
                }
            }
            // redirect
            Session::flash('flash_message', 'Successfully created Job Industry!');
            return redirect()->back();
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$ret = $this->deleteJobIndustrybyId($id);        
        if($ret == 1)           
            return Redirect::to(Session::get('lang').'/jobcategory');
        else 
            return "error in delete";
    }

    public function getAllJobIndustryList($type = 0){
Log::info('parent job industry --- '.$type);
	    if($type == 0)
    		return job_industry::orderBy('job_industry')->get();
    	    else
    	    	return job_industry::where('type','=',$type)->orderBy('job_industry')->get();
    }
    
    public function getAllJobIndustrybyId($id){
    	return job_industry::find(decrypt($id));
    }
    
    public function deleteJobIndustrybyId($id)
    {
    	$job_industry = $this->getAllJobIndustrybyId($id);
    	$ret = 0;
    	if($job_industry!= null)
    		$ret = $job_industry->delete();
    		return $ret;
    }
    
    
}

