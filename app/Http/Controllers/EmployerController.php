<?php

namespace jobready365\Http\Controllers;

use jobready365\employer;
use jobready365\company;
use Log;
use Validator;
use Input;
use Redirect;
use Session;
use DB;

class EmployerController extends Controller
{
	public function __construct()
	{
	}
	
	public function index()
    { 
        $employer = $this->getAllEmployer();
        return view('employer.index', compact('employer'));
    }
    
    public function create()
    {	
    	//$country= app('jobready365\Http\Controllers\HomeController')->getAllCountry();
    	$city = app('jobready365\Http\Controllers\HomeController')->getAllCity();
    	$township = app('jobready365\Http\Controllers\HomeController')->getAllTownship();
        return view("employer.create",compact('city','country','township'));
    }
    
    public function edit($user_id)
    {
    	//$country= app('jobready365\Http\Controllers\HomeController')->getAllCountry();
    	$city = app('jobready365\Http\Controllers\HomeController')->getAllCity();
    	$township = app('jobready365\Http\Controllers\HomeController')->getAllTownship();
    	$employer = $this->getEmployerbyId($user_id);          
        return view('employer.edit', compact('employer','city','country','township'));
    }
    
    public function update($id)
    {   
    	try{
	            $validator=$this->validate_data();
	            if ($validator->fails()) {
	            	return Redirect::to('employer/'.encrypt($id).'/edit')
	            	->withErrors($validator)
	            	->withInput(Input::except('name'));
	            }
	            else {
	            	$employer = employer::findOrFail(decrypt($id));
        			$this->save_employer_data($employer);
	            }
    	}catch (\Exception $e){
                Log::info('duplicate employer: '.$e);
                $errorCode = $e->errorInfo[1];
                if($errorCode == 1062){
                    Session::flash('duplicate_message', 'Duplicate! Already Exist');
                    return redirect()->back();
                }
            }
            return redirect()->back();
       }
       
    // common function //
    public function getAllEmployer(){
    	$employer =DB::table('employers as e')
    	//->join('companies as c', 'e.user_id', '=', 'c.user_id')
    	->join('townships','e.township_id','=','townships.id')
    	->join('cities','e.city_id','=','cities.id')
    	->select('e.*','townships.township','cities.city')
    	->orderBy('e.created_at','desc')
    	->get();
    	return $employer;
    }

    public function getEmployerbyId($id,$encrypt=1){
    	$id = ($encrypt==1)?decrypt($id):$id;
       $employer =DB::table('employers as e')
       //->join('companies as c', 'e.user_id', '=', 'c.user_id')
       ->select('*')
       ->where('e.user_id', $id)
       ->get();
       return $employer;
    }    
    
    public function getEmployerbyUserId($uid){
       $employer =DB::table('employers as e')
       //->join('companies as c', 'e.user_id', '=', 'c.user_id')
       ->select('*')
       ->where('e.user_id', $uid)
       ->get();
       return $employer;
    }
    
    public function checkValidEmployerbyUserId($uid){
    	//echo date("Y-m-d h:i:s");
       $employer =DB::table('employers as e')
       //->join('companies as c', 'e.user_id', '=', 'c.user_id')
       ->select('*')
       ->where('e.user_id', $uid)
       ->where('e.expired_date','>=',date("Y-m-d h:i:s"))
       ->get();
      /* if(count($employer) == 1)
       		return 1;
       else return 0;*/
      // print_r($employer);
       return $employer;
    }
    
    public function getCompanyIdbyUserId($uid){
    	return company::where('user_id', decrypt($uid))->select('id')->get();
    }
    
    public function validate_data(){
    	$rules = array(
    			'name'       => 'required',
    			'mobile_no'       => 'required',
    			//'company_name' => 'required',
    			'address' => 'required',
    			'township' => 'required',
    	);
    	return Validator::make(Input::all(), $rules);
    }
    
    public function save_employer_data($employer)
    {    	
    	$employer->name = Input::get('name');    	
    	$employer->mobile_no = Input::get('mobile_no');    
    	$employer->email = Input::get('email');
    	$employer->facebook = Input::get('facebook');
    	$employer->viber= Input::get('viber');
    	$employer->address = Input::get('address');
    	$employer->township_id = Input::get('township');
    	//$employer->postal_code = Input::get('postal_code');
    	$employer->city_id = Input::get('city');
    	$employer->country_id = 1;
    	Log::info('save employer: '.$employer);
    	$employer->save();
    }
    
    
    /*
     public function getEmployerbyToken($id){
     $user =DB::table('users')
     ->select('user.id')
     ->where('user.api_token', $id)
     ->get();
     
     $employer =DB::table('employers as e')
     ->join('companies as c', 'e.user_id', '=', 'c.user_id')
     ->select('e.id','c.id as cid','e.name','e.mobile_no','c.company_name','c.address','c.township','c.postal_code','c.city_id','c.country_id','c.mobile_no as company_contact','c.email as company_email','c.website','c.description')
     ->where('e.user_id', $user->id)
     ->get();
     return $employer;
     }
     */
    /*
     public function showEmployerbyId($id){
     $employer =DB::table('employers as e')
     ->join('companies as c', 'e.user_id', '=', 'c.user_id')
     ->join('townships as tsp','c.township_id','=','tsp.id')
     ->join('cities as city', 'city.id','=','c.city_id')
     ->join('countries as country', 'country.id','=','c.country_id')
     ->select('e.id','c.id as cid','e.name','e.mobile_no','c.company_name','c.company_logo','c.description','c.address','c.township_id','c.postal_code','c.city_id','c.country_id','c.mobile_no as company_contact','c.email as company_email','c.website','c.description','tsp.township','city.city','country.country')
     ->where('e.id', $id)
     ->get();
     return $employer;
     }*/
    
    /*
    public function save_company_data($company)
    {
    	$company->company_name = Input::get('company_name');
    	$company->mobile_no = Input::get('contact_no');
    	$company->address = Input::get('address');
    	$company->township = Input::get('township');
    	$company->postal_code = Input::get('postal_code');
    	$company->city_id = Input::get('city');
    	$company->country_id = Input::get('country');
    	$company->email = Input::get('email');
    	$company->website = Input::get('website');
    	$company->description = Input::get('description');
    	Log::info('save company: '.$company);
    	$company->save();
    }
*/
}
