<?php

namespace jobready365\Http\Controllers;

use jobready365\company;
use Log;
use Validator;
use Input;
use Redirect;
use Session;
use DB;
use Auth;
use Ramsey\Uuid\Uuid;

class CompanyController extends Controller
{
	public function index()
	{
		$company = $this->getAllCompany();
		Log::info('company: '.$company );
		return view('company.index', compact('company'));
	}
	
	public function create()
	{
		$city = app('jobready365\Http\Controllers\HomeController')->getAllCity();
		$township = app('jobready365\Http\Controllers\HomeController')->getAllTownship();
		$job_industry = app('jobready365\Http\Controllers\HomeController')->getAllJobIndustry(1);
		return view("company.create",compact('city','township','job_industry'));
	}
	
	public function store()
	{
		Log::info('store company: ');
		try{			
			$validator=$this->validate_data();
			if ($validator->fails()) {
				Session::flash('duplicate_message', 'Fail in Validation');
				return redirect('company/create')
				->withErrors($validator)
				->withInput();
			} else {
				Log::info('go to save company: ');
				$company = new  company;
				$company->id = Uuid::uuid4()->getHex();
				$company->user_id = Auth::user()->id;
				$company->type = Input::get('type');
				$this->save_company_data($company);
				$this->upload_logo($company);
			}
		}catch (\Exception $e){
			Log::info('duplicate company: '.$e);
			$errorCode = $e->errorInfo[1];
			if($errorCode == 1062){
				Session::flash('duplicate_message', 'Duplicate! Already Exist');
				return redirect()->back();
			}
		}
		return Redirect::to(Session::get('lang').'/dashboard');
	}
	
	public function show($id)
	{
		$company = $this->getCompanybyId($id);
		Log::info('show'.$company);
		return view('company.show',compact('company'));
	}
	
	public function edit($id)
	{
		$city = app('jobready365\Http\Controllers\HomeController')->getAllCity();
		$township = app('jobready365\Http\Controllers\HomeController')->getAllTownship();
		$company = $this->getCompanybyId($id);		
		$job_industry = app('jobready365\Http\Controllers\HomeController')->getAllJobIndustry($company[0]->type);
		return view('company.edit', compact('company','city','job_industry','township'));
	}
	
	public function update($id)
	{
		if (Auth::user()->is_admin){
			$this->update_feature($id);
		}else{
			$this->update_company($id);
		}
		$company = $this->getAllCompany();
		return view('company.index', compact('company'));
	}
	
	public function update_company($id)
	{
		try{
			$validator=$this->validate_data();
			
			if ($validator->fails()) {
				Session::flash('duplicate_message', 'Fail in Validation');
				return redirect('company/create')
				->withErrors($validator)
				->withInput();
			} else {
				$company = company::findOrFail(decrypt($id));
				$this->save_company_data($company);
				$this->upload_logo($company);
				//$company->save();
			}
		}catch (\Exception $e){
			Log::info('error update company: '.$e);
			return redirect()->back();
		}
	}
	
	public function update_feature($id)
	{	Log::info('update company feature: ');
		try{
			$company = company::findOrFail(decrypt($id));
			$company->is_feature = Input::get('hfeature');
			$company->save();
		}catch (\Exception $e){
			Log::info('error update feature: '.$e);
			return redirect()->back();
		}
	}
	
	public function destroy($id)
	{
		$ret = $this->deleteCompanybyId($id);
		if($ret == 1)
			return Redirect::to(Session::get('lang').'/company');
			else
				return "error in delete";
	}
	
	// common function //
	
	public function getCompanyId(){
		return $this->edit(encrypt(Input::get('company')));
	}
	
	public function getAllFeaturedCompany(){
		$feature_company = DB::table('companies as c')
		->join('jobs as j','c.id','=','j.company_id')
		->select(DB::raw('count(*) as job_count, j.company_id, c.company_name, c.company_logo'))
		->where('c.is_feature','1')
		->where('c.company_logo', '!=', '')
		->whereDate('j.open_date', '<=', date("Y-m-d")) //'2016-11-02'
		->whereDate('j.close_date', '>=', date("Y-m-d"))
		->where('j.is_active', '=', 1)		
		->groupBy('j.company_id', 'c.company_name', 'c.company_logo')
		->orderBy('job_count','desc')->paginate(15);
		return $feature_company;
	}
	
	public function getAllCompany(){
	Log::info('company: '.Auth::user()->user_role );
		if (Auth::user()->user_role == 1 || Auth::user()->user_role == 2)
			return DB::table('companies as c')
			->join ('users as u ','u.id','=', 'c.user_id')
			->join('townships as tsp','c.township_id','=','tsp.id')
			->join('cities','c.city_id','=','cities.id')
			->select('c.*','tsp.township','cities.city','u.telephone_no')
			->where('c.is_active','1')
			->orderBy('c.company_name')
			->get();
		else
			return DB::table('companies as c')
			->join('townships as tsp','c.township_id','=','tsp.id')
			->join('cities','c.city_id','=','cities.id')
			->select('c.*','tsp.township','cities.city')
			->where('c.is_active','1')
			->where('c.user_id',Auth::user()->id)
			->orderBy('c.company_name')
			->get();
	}
	
	public function getAllCompanybyUserID($uid){ //api
		return DB::table('companies as c')
		->join('townships as tsp','c.township_id','=','tsp.id')
		->join('cities','c.city_id','=','cities.id')
		->select('c.*','tsp.township','cities.city')
		->where('c.is_active','1')
		//->where('c.user_id',decrypt($uid))
		->where('c.user_id',$uid)
		->orderBy('c.company_name')
		->get();
	}
	
	public function getCompanybyId($id,$encrypt=1){
		$id = ($encrypt==1)?decrypt($id):$id;
		return DB::table('companies as c')
		->join('job_industries as ji', 'c.job_industry_id','=','ji.id')
		->join('townships as tsp','c.township_id','=','tsp.id')
		->join('cities','c.city_id','=','cities.id')
		->join('countries','c.country_id','=','countries.id')
		->select('c.*','tsp.township','cities.city','countries.country','ji.job_industry')
		//->where('c.is_active','1')
		->where('c.id',$id)
		->get();
	}
	
	public function deleteCompanybyId($id, $encrypt=1)
	{
		$id = ($encrypt==1)?decrypt($id):$id;
		Log::info('deleteCompanybyId: '. $id);
		$company = company::findOrFail($id);
		$company->is_active = 0;
		return $company->save();
	}
	
	public function validate_data(){
		$rules = array(
				'company_name' => 'required',
				'job_industry' => 'required',
				'mobile_no' => 'required',
				'address' => 'required',
				'primary_contact_person' => 'required',
				'primary_mobile' => 'required',
				//'logo' => 'image|max:5000'
				//'township' => 'required',
				//'description' => 'required',
		);
		return Validator::make(Input::all(), $rules);
	}
	
	/*public function upload_logo_web($company){
		if(Input::file('logo') != null){
			Log::info('logo file is not null');
			$file = Input::file('logo');
			//Log::info('file: '. $file);
			$destinationPath = 'uploads/company_logo';
			$extension = $file->getClientOriginalExtension();
			$keys = array_merge(range(0, 9), range('a', 'z'));
			$fileName = array_rand($keys).'.'.$extension;
			//$fileName = $file->getClientOriginalName();
			Log::info('$fileName: '. $fileName);
			
			$upload_success = $file->move($destinationPath, $fileName);
			Log::info('logo upload_success: '. $upload_success);
			// IF UPLOAD IS SUCCESSFUL SEND SUCCESS MESSAGE OTHERWISE SEND ERROR MESSAGE
			if ($upload_success) {
				$company->company_logo = $fileName;
			}
			else{
				echo 'logo cannto be saved.';
			}
			$last_id = $company->id;
			$company->save();
			Log::info('last id: '.$last_id);
		}
	}*/
	
	public function upload_logo($company){
		$last_id = '';
		
		Log::info('company id '.$company->id);
		Log::info('upload logo file');
		
		if(isset($_FILES['logo']['name'])){
			//$file = Input::file('resume-photo');
			
			$file_name = $_FILES['logo']['name'];
			//echo $file_name;
			$file_size =$_FILES['logo']['size'];
			$file_tmp =$_FILES['logo']['tmp_name'];
			$file_type=$_FILES['logo']['type'];
			//$file_ext=strtolower(end(explode('.',$file_name)));
			$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
			//echo $file_name;
			$extensions= array("jpg","jpeg","png");
			
			if(in_array($file_ext,$extensions)=== false){
				Log::info('invalid file type ');
				$last_id = "invalid file type"; //"extension not allowed, please choose a JPEG or PNG file.";
				return $last_id;
			}
			
			$max_size = 5 * 1024 * 1024;
			if($file_size > $max_size ){
				$last_id = "invalid file size";	//"File size must be excately 5 MB";
				return $last_id;
			}
			
			$destinationPath = 'uploads/company_logo/';
			$uploadfile = $destinationPath . basename(time()."_".$_FILES['logo']['name']);
			
			if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadfile )) {
				$company->company_logo = time()."_".$_FILES['logo']['name'];
				$last_id = $company->id;
				$company->save();
				Log::info('success logo upload: ');
				
				//echo "File is valid, and was successfully uploaded.\n";
			}
		}
		return $last_id;
	}
	
	public function save_company_data($company)
	{	
		$company->company_name = Input::get('company_name');
		$company->address = Input::get('address');
		$company->township_id = Input::get('township');
		$company->job_industry_id = Input::get('job_industry');
		//$company->postal_code = Input::get('postal_code');
		$company->city_id = Input::get('city');
		$company->country_id = 1;
		$company->mobile_no = Input::get('mobile_no');
		$company->email = Input::get('email');
		$company->website = Input::get('website');
		$company->description = Input::get('description');
		$company->primary_contact_person = Input::get('primary_contact_person');
		$company->primary_mobile = Input::get('primary_mobile');
		$company->secondary_contact_person= Input::get('secondary_contact_person');
		$company->secondary_mobile = Input::get('secondary_mobile');
		Log::info('save company: '.$company);
		$last_id = $company->id;
		if($company->save() == 1)
			return $last_id;
		else return -1;
		//return Response::json(array('success' => true, 'last_insert_id' => $data->id), 200);
	}
}