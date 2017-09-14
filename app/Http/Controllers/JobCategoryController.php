<?php

namespace jobready365\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use jobready365\job_category;
use jobready365\job_industry;
use Log;
use Validator;
use Input;
use Redirect;
use Session;
use DB;
use jobready365\township;

class JobCategoryController extends Controller
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
    	$job_category = $this->getAllJobCategoryList(); 
    	$job_industry= app('jobready365\Http\Controllers\JobIndustryController')->getAllJobIndustryList(); 
        $township = app('jobready365\Http\Controllers\TownshipController')->getAllTownshipCity();
        $city = app('jobready365\Http\Controllers\HomeController')->getAllCity();
        
        return view('jobcategory.index', compact('job_category','township','city','job_industry'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("jobcategory.create");
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
            return Redirect::to('jobcategory/create')
                ->withErrors($validator)
                ->withInput(Input::except('name'));
        } else {
            try{
                $job_category = new job_category;
                $job_category->category = Input::get('name');
                $job_category->save();
            }catch (\Exception $e){
                Log::info('duplicate category: ');
                $errorCode = $e->errorInfo[1];
                if($errorCode == 1062){
                    Session::flash('duplicate_message', 'Duplicate Category! Already Exist');
                    return redirect()->back();
                }
            }
            // redirect
            Session::flash('flash_message', 'Successfully created Job Category!');
            return redirect()->back();
        }
/*
    	$company = company::findOrFail("f050e6c5c6d64d90aa87e908ab5dda45");
    	Log::info('file: '. Input::file('file'));
    	if(Input::hasFile('file'))
    	{	Log::info('file: ');
    		$f = Input::file('file');    		
    		Log::info('ff' .base64_encode(file_get_contents($f->getRealPath())));
    		$company->company_logo = base64_encode(file_get_contents($f->getRealPath()));
    		$company->save();
    	}
        */
        /*
        $file = Input::file('file');
        Log::info('file: '. $file);
        // SET UPLOAD PATH
        
        $destinationPath = 'uploads';
        
        // GET THE FILE EXTENSION
        
        $extension = $file->getClientOriginalExtension();
        Log::info('$extension: '. $extension);
        // RENAME THE UPLOAD WITH RANDOM NUMBER
        
        $fileName = rand(11111, 99999) . '.' . $extension;
        Log::info('$$fileName: '. $fileName);
        // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
        
        $upload_success = $file->move($destinationPath, $fileName);
        Log::info('$$$upload_success: '. $upload_success);
        // IF UPLOAD IS SUCCESSFUL SEND SUCCESS MESSAGE OTHERWISE SEND ERROR MESSAGE
        
        if ($upload_success) {
        
        	$company = company::findOrFail("be6247640bd74edd8a21d1681965dc53");
        	//Log::info('file: '. Input::file('file'));
        	//if(Input::hasFile('file'))
        	//{	Log::info('file: ');
        	//$f = Input::file('file');
        	//Log::info('ff' .base64_encode(file_get_contents($f->getRealPath())));
        	//$company->company_logo = base64_encode(file_get_contents($f->getRealPath()));
        	$company->company_logo=$fileName;
        	$company->save();
        	//}
        	return Redirect::to('/')->with('message', 'Image uploaded successfully');
        
        }
        */
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   /* public function show($id)
    {   
    	$job_category = $this->getJobCategorybyId(decrypt($id));            
            return view('jobcategory.show')->withTask($job_category);
    }*/

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function edit($id)
    {
        $job_category = $this->getJobCategorybyId(decrypt($id));       
        return view('jobcategory.edit', compact('job_category'));
    }*/

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function update($id)
    {   Log::info('update category: '.$id);
        $rules = array(
            'name'       => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
        if ($validator->fails()) {
            return Redirect::to('jobcategory/'.$id.'/edit')
                ->withErrors($validator)
                ->withInput(Input::except('name'));
                //return $validator->errors()->all();
        } else {
            // store
            try{
                $job_category = job_category::findOrFail($id);
                $job_category->category = Input::get('name');
                $job_category->save();
            }catch (\Exception $e){
                Log::info('duplicate category: ');
                $errorCode = $e->errorInfo[1];
                if($errorCode == 1062){
                    Session::flash('duplicate_message', 'Duplicate! Already Exist');
                    return redirect()->back();
                }
            }
            return Redirect::to(Session::get('lang').'/jobcategory');

        }
    }*/

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$ret = $this->deleteJobCategorybyId($id);        
        if($ret == 1)           
            return Redirect::to(Session::get('lang').'/jobcategory');
        else 
            return "error in delete";
    }

    // common function //
    public function getAllJobCategory(){
     //   return job_category::orderBy('category')->get();
    	return job_category::pluck('category','id');
    }
    
    public function getAllJobCategoryList(){
    	return job_category::orderBy('category')->get();
    }    
    
    public function getJobCategorybyId($id){
    	return job_category::find(decrypt($id));
    }
    
    public function deleteJobCategorybyId($id)
    {
        $job_category = $this->getJobCategorybyId($id);   
        $ret = 0;
        if($job_category != null)
            $ret = $job_category->delete();
        return $ret;       
    }
        
    public function category_autocomplete(Request $req){
    	
    	$str = $req->get('term','');
    	
    	$cat =  DB::table('job_categories')
    	//->where('category', 'like', '%'.$arr[sizeof($arr)-1].'%')
    	->where('category', 'like', '%'.$str.'%')
    	->select('id','category')->get();
    	$data=array();
        foreach ($cat as $c) {
        	$data[]=array('id'=>$c->id,'value'=>$c->category);
        }
        if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
    }

    
}

