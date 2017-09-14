<?php
namespace jobready365\Http\Controllers;

use Log;
use Validator;
use Input;
use Redirect;
use Session;
use DB;
use Auth;
use Ramsey\Uuid\Uuid;
use jobready365\applicant;
use jobready365\experience;
use jobready365\education;
use jobready365\skill;
use jobready365\refree;
use jobready365\user;
use jobready365\category;
use jobready365\qualification;
use File;

class ApplicantController extends Controller
{
    public function index()
    {
    	$applicant = $this->getAllApplicant();    	
    	return view('applicant.index', compact('applicant'));
    }

    public function create()
    {
    	$city = app('jobready365\Http\Controllers\HomeController')->getAllCity();
        return view("applicant.create",compact('city','country'));
    }

    public function show($id)
    {
        $applicant = $this->showApplicantbyId($id);  
        $education =  education::where('applicant_id', $applicant[0]->user_id)->select('*')->get();
        $skill =  skill::where('user_id', $applicant[0]->user_id)->select('*')->get();
        $experience =  experience::where('user_id', $applicant[0]->user_id)->select('*')->get();
        $refree =  refree::where('applicant_id', $applicant[0]->user_id)->select('*')->get();
        return view('applicant.show', compact('applicant','education','skill','experience','refree'));
    }

    public function edit($user_id)
    {   
    	$applicant = $this->getApplicantbyId($user_id);
        $city = app('jobready365\Http\Controllers\HomeController')->getAllCity();
        $township = app('jobready365\Http\Controllers\HomeController')->getAllTownshipbyCity($applicant[0]->city_id);  
       
        return view('applicant.edit', compact('applicant','city','township'));
       // return view('applicant.edit', compact('applicant','city','country','township','education','skill','experience','refree'));
    }    

    public function update($id)
    {
        try{
	            $validator=$this->validate_data();
	            Log::info('validator : '.$validator->fails());
	            if ($validator->fails()) {
	            	Session::flash('duplicate_message', 'Fail in Input Validation');	            	
	            	return redirect()->back()
	            	->withErrors($validator)
	            	->withInput();
	            }
	            else {                	
	            	$this->update_data($id);
	            }	        
    	}catch (\Exception $e){
        	Log::info('duplicate applicant: '.$e);
        }
        return redirect()->back();
    }

	public function browse_cv()
    {   	
       $employer =DB::table('employers as e')
       //->join('companies as c', 'e.user_id', '=', 'c.user_id')
       ->select('*')
       ->where('e.user_id', Auth::user()->id)
       ->get();
	      $applicant = null;
    	return view('applicant.browse_cv', compact('employer','applicant'));
    }

    public function getJobSeekerbyCount($filter){
    	if($filter == 'category')
    		return DB::table('applicants as js')
    		->join('users as u','js.user_id','=','u.id')
    		->join('job_categories as jc','u.category_id','=','jc.id')
    		->where('js.cv_views', '1')
    		->select(DB::raw('count(*) as js_count, jc.id, jc.category'))
    		->groupBy('jc.id', 'jc.category')
    		->orderBy('js_count','desc')->limit(8)->get();    		
    	elseif($filter == 'location')
	    	return DB::table('applicants as js')
	    	->join('townships as t','js.township_id','=','t.id')
	    	->where('js.cv_views', '1')
	    	->select(DB::raw('count(*) as js_count, t.id, t.township'))
	    	->groupBy('t.id',  't.township')
	    	->orderBy('js_count','desc')->limit(8)->get();    	
    } 
    
    // common function //
    public function getFilterApplicant($str = ''){
    	if($str == '')
    		$applicant =DB::table('applicants as a')
    		->join('townships as tsp', 'tsp.id','=','a.township_id')
    		->join('cities as city', 'city.id','=','a.city_id')
    		->join('countries as country', 'country.id','=','a.country_id')
    		//->join('job_categories as cat','cat.id','=', 'a.job_category_id')
    		->where('a.cv_views', '=', 1)
    		->select('a.*','city.city','country.country','tsp.township')
    		->orderBy('name')->get();
    	else
    		$applicant =DB::table('applicants as a')
    		->join('townships as tsp', 'tsp.id','=','a.township_id')
    		->join('cities as city', 'city.id','=','a.city_id')
    		->join('countries as country', 'country.id','=','a.country_id')
    		//->join('job_categories as cat','cat.id','=', 'a.job_category_id')
    		->where('a.cv_views', '=', 1)
    		->where('a.desired_position', 'like', '%' . $str . '%')
    		->select('a.*','city.city','country.country','tsp.township')
    		->orderBy('name')->get();
    	return $applicant;
    		
    }
    
    public function get_browse_cv_list($str = ''){
    	$applicant = $this->getFilterApplicant($str);
    	return Datatables::of($applicant)
            ->editColumn('result',function($job){
            	$link_url = Session::get('lang').'/applicant/'.encrypt($applicant->id);
                $result_data = "<a href=".$link_url." class='applied'><div class='row'><div class='col-md-2 hidden-sm hidden-xs'>";
                
                $img_url = $applicant->photo == '' ? "/uploads/resume-photo/logo.jpg" : "/uploads/company_logo/".$applicant->photo; 
                $logo = "<img src='".$img_url."' width='80' height='80' style='border:2px solid #999999; border-radius:5px;'></div>" ;
               
                $link = "<div class='col-md-8 col-sm-12 job-title'><h5>".$applicant->name."</h5><p>".$applicant->desired_position."</p>";
                $location = "<p class='job-location'>".$applicant->township." - ".$applicant->city."</p></div>";
               // $location = "<div class='col-lg-3 col-md-3 col-sm-5 col-xs-12 job-location'><p><strong>".$job->township." - ".$job->city."</strong><br>".$job->close_date."</p></div>";
                 
                $result_data .= $logo.$link.$location."</div></a>";
                //Log::info('result_data: '.$result_data);
                return $result_data;})           
            ->make(true);
    }
    
    public function getAllApplicant(){
	   
    	if(Auth::user()->user_role == 1 || Auth::user()->user_role == 2){
    		$applicant =DB::table('applicants as a')
    		->join ('users as u ','u.id','=', 'a.user_id')
    		->join('townships as tsp', 'tsp.id','=','a.township_id')
    		->join('cities as city', 'city.id','=','a.city_id')
    		->join('countries as country', 'country.id','=','a.country_id')
    		->select('a.*','city.city','country.country','tsp.township','u.telephone_no')
    		->orderBy('name')->get();    		
    	}    		
    	else if(Auth::user()->user_type == 1){
    		$applicant =DB::table('applicants as a')
    		->join ('users as u ','u.id','=', 'a.user_id')
    		->leftjoin('townships as tsp', 'tsp.id','=','a.township_id')
    		->leftjoin('cities as city', 'city.id','=','a.city_id')
    		->leftjoin('countries as country', 'country.id','=','a.country_id')    		
    		->select('a.*','city.city','country.country','tsp.township')
    		->where('cv_views', '1')
    		->orderBy('name')->get();     		
    	}
    		
    	return $applicant;   
    }    
    
    public function view_cv($id, $jid)
    {
        $applicant = $this->showApplicantbyIdAPI($id, $jid);  
        $education =  education::where('applicant_id', $applicant[0]->user_id)->select('*')->get();
        $skill =  skill::where('user_id', $applicant[0]->user_id)->select('*')->get();
        $experience =  experience::where('user_id', $applicant[0]->user_id)->select('*')->get();
        $refree =  refree::where('applicant_id', $applicant[0]->user_id)->select('*')->get();
       // return view('applicant.show', compact('applicant','education','skill','experience','refree','township'));
        return view('applicant.show', compact('applicant','education','skill','experience','refree'));
    }   
    
    public function getApplicantbyId($uid, $encrypt=1){
    	$uid= ($encrypt==1)?decrypt($uid):$uid;
    	$applicant =DB::table('applicants as a')
        	->select('*')
       	 	->where('a.user_id', $uid)
        	->get();
        return $applicant;
    }    
    
    public function showApplicantbyIdAPI($uid, $jid, $encrypt=1){
    	//Log::info('$applicant id: '. $uid);
        Log::info('$job id b4 decrypt: '. $jid);
        if($encrypt == 1) {
        	$uid = decrypt($uid);
        	$jid = $jid == "0" ? 0 :decrypt($jid);
        }
        Log::info('$applicant id: '. $uid);
        Log::info('$job id: '. $jid);
        $applicant =DB::table('applicants as a')
        ->leftjoin('townships as tsp', 'tsp.id','=','a.township_id')
        ->leftjoin('cities as city', 'city.id','=','a.city_id')
        ->leftjoin('countries as country', 'country.id','=','a.country_id')
        ->select('a.*','city.city','country.country','tsp.township')
        ->where('a.user_id', $uid)
        ->get();
        Log::info('$applicant: '. $applicant);
        $applicant[0]->jid = $jid;
        if($applicant != null && count($applicant) > 0){
            $education =  education::where('applicant_id', $uid)->select('*')->get();
            if($education != null && count($education) > 0) $applicant[0]->education = $education;

            $skill =  skill::where('user_id', $uid)->select('*')->get();
            if($skill != null && count($skill) > 0) $applicant[0]->skill = $skill;

            $qualification =  qualification::where('applicant_id', $uid)->select('*')->get();
            if($qualification != null && count($qualification) > 0) $applicant[0]->qualification = $qualification;
                
            $experience =  experience::where('user_id', $uid)->select('*')->get();
            if($experience != null && count($experience) > 0) $applicant[0]->experience = $experience;
                
            $refree =  refree::where('applicant_id', $uid)->select('*')->get();
            if($refree != null && count($refree) > 0) $applicant[0]->refree = $refree;
        }
        return $applicant;
    } 
    
    public function showApplicantbyId($id, $encrypt=1){
    	if($encrypt == 1) $id = decrypt($id);
    	$applicant =DB::table('applicants as a')
    	->leftjoin('townships as tsp', 'tsp.id','=','a.township_id')
    	->leftjoin('cities as city', 'city.id','=','a.city_id')
    	->leftjoin('countries as country', 'country.id','=','a.country_id')
    	->select('a.*','city.city','country.country','tsp.township')
    	->where('a.id', $id)
    	->get();
    	Log::info('$applicant: '. $applicant);
    	return $applicant;
    }
 
    public function deleteApplicantbyId($id)
    {
    	$applicant = $this->getApplicantbyId(decrypt($id));
    	$ret = 0;
    	if($applicant != null)	return $applicant->delete();
    }

    public function validate_data(){
    	Log::info('validate_data: ');
    	$rules = array(
    			'name'      => 'required',
    			'father_name'      	=> 'required',
    			'dob'       	  	=> 'required',                
    			'mobile_no'       	=> 'required',
    			'address'		  	=> 'required',
    	);
    	return Validator::make(Input::all(), $rules);
    }
    
    public function save_applicant_data($applicant){
    	try{
    		/* Log::info('gender: '.Input::get('gender'));	
    		Log::info('cv_view: '.Input::get('cv_view'));	
    		Log::info('name: '.Input::get('name'));	
    		Log::info('father_name: '.Input::get('father_name'));	
    		Log::info('dob: '.Input::get('dob'));	
    		Log::info('pob: '.Input::get('pob'));	
    		Log::info('marital_status: '.Input::get('marital_status'));	
    		Log::info('religion: '.Input::get('religion'));	
    		Log::info('nrc: '.Input::get('nrc'));	
    		Log::info('mobile: '.Input::get('mobile_no'));	
    		Log::info('email: '.Input::get('email'));	
    		Log::info('expected_salary: '.Input::get('expected_salary'));	
    		Log::info('address: '.Input::get('address'));	
    		Log::info('current_position: '.Input::get('current_position'));	
    		Log::info('desired_position: '.Input::get('desired_position'));	
    		Log::info('chkDriving: '.Input::get('chkDriving'));	*/
    		
    		if(Input::get('dob') != '' || Input::get('dob') != null) {
    			if(date_format(date_create(Input::get('dob')), 'Y-m-d') > date("Y-m-d"))
    				return 'over';
    		}
    		
	    	$applicant->gender = Input::get('gender');
	    	$applicant->cv_views = !Input::get('cv_view');
	    	$applicant->name = Input::get('name');
	    	$applicant->father_name = Input::get('father_name');
	    	$applicant->date_of_birth = Input::get('dob');
	    	$applicant->place_of_birth = Input::get('pob');
	    	$applicant->marital_status = Input::get('marital_status');
	    	$applicant->nationality= Input::get('nationality');
	    	$applicant->religion= Input::get('religion');    
	    	$applicant->nrc_no= Input::get('nrc');    
	    	$applicant->mobile_no = Input::get('mobile_no');
	    	$applicant->email = Input::get('email');
	    	$applicant->expected_salary = Input::get('expected_salary');
	    	$applicant->address = Input::get('address');
	    	$applicant->township_id = Input::get('township');
	    	//$applicant->postal_code = Input::get('postal_code');
	    	$applicant->city_id = Input::get('city');
	    	$applicant->country_id = 1;
	    	//$applicant->country_id = Input::get('country');	 
	    	$applicant->current_position = Input::get('current_position');
	    	$applicant->desired_position= Input::get('desired_position');
	    	$applicant->driving_license = Input::get('chkDriving') == null ? 0 : 1;
	    	
	    	Log::info('resume-photo file: '. Input::file('resume-photo'));
	        if(Input::file('resume-photo') != null){
		    	$file = Input::file('resume-photo');
		    	Log::info('file: '. $file);	
		    	$destinationPath = 'uploads/resume-photo';
		    	$extension = $file->getClientOriginalExtension();	    
		    	$keys = array_merge(range(0, 9), range('a', 'z'));		    	
		    	$fileName = array_rand($keys).'.'.$extension;
		    	//$fileName = $file->getClientOriginalName();
		    	Log::info('$fileName: '. $fileName);
		    	
		    	$upload_success = $file->move($destinationPath, $fileName);
		    	Log::info('logo upload_success resume-photo: '. $upload_success);
		    	// IF UPLOAD IS SUCCESSFUL SEND SUCCESS MESSAGE OTHERWISE SEND ERROR MESSAGE    	
		    	if ($upload_success) {      	
			    	$applicant->photo = $fileName;
		    	}
		    	else{
		    			Session::flash('duplicate_message', 'Resume photo upload fail.');
		    			return redirect()->back();
		    		}

	    	}
	    	
	    	Log::info('attach_cv file: '. Input::file('attach_cv'));
	    	if(Input::file('attach_cv') != null){
	    		$file = Input::file('attach_cv');
	    		Log::info('file: '. $file);
	    		$destinationPath = 'uploads/cv';
	    		$extension = $file->getClientOriginalExtension();
	    		$keys = array_merge(range(0, 9), range('a', 'z'));
	    		$fileName = array_rand($keys).'.'.$extension;
	    		//$fileName = $file->getClientOriginalName();
	    		Log::info('$fileName: '. $fileName);
	    		
	    		$upload_success = $file->move($destinationPath, $fileName);
	    		Log::info('upload_CV success : '. $upload_success);
	    		// IF UPLOAD IS SUCCESSFUL SEND SUCCESS MESSAGE OTHERWISE SEND ERROR MESSAGE
	    		if ($upload_success) {
	    			$applicant->attach_cv = $fileName;
	    		}
	    		else{
	    			Session::flash('duplicate_message', 'CV upload fail.');
	    			return redirect()->back();
	    		}	    		
	    	}
	    	Log::info('save applicant: '.$applicant);
	    	return $applicant->save();
	    }catch(\Exception $ex){
	    	Session::flash('duplicate_message', 'Cannot Save. Error in update profile information.');	
	    	return redirect()->back();	    
	    }
    }
    
    public function upload_picture($applicant){ //api
    	$last_id = '';    $resume_photo = '';
    	Log::info('picture upload start: '.$_FILES['resume-photo']['name']);
    	//if(Input::file('resume-photo') != null){
    	if(isset($_FILES['resume-photo']['name'])){
    		//$file = Input::file('resume-photo');
    		
    		$file_name = $_FILES['resume-photo']['name'];
    		//echo $file_name;
    		$file_size =$_FILES['resume-photo']['size'];
    		$file_tmp =$_FILES['resume-photo']['tmp_name'];
    		$file_type=$_FILES['resume-photo']['type'];
    		//$file_ext=strtolower(end(explode('.',$file_name)));
    		$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    		//echo $file_name;
    		$extensions= array("jpeg","jpg","png");
    		
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
    		
    		$destinationPath = 'uploads/resume-photo/';
    		$uploadfile = $destinationPath . basename(time()."_".$_FILES['resume-photo']['name']);
    		//echo $uploadfile ;
    		if (move_uploaded_file($_FILES['resume-photo']['tmp_name'], $uploadfile )) {
    			$applicant->photo = time()."_".$_FILES['resume-photo']['name'];
    			$last_id = $applicant->id;
    			$resume_photo = $applicant->photo;
    			$applicant->save();
    			Log::info('success photo upload: ');
    			//echo "File is valid, and was successfully uploaded.\n";
    		}
    		Log::info('last id: '.$last_id);
    	}
    	
    	$arr = array('last_id' => $last_id, 'resume-photo' => $resume_photo);
    	//return json_encode($arr);
    	return $arr;
    }
    
    public function upload_CV($applicant){ //api
    	$last_id = '';
		Log::info('cv upload start: '.$_FILES['attach_cv']['name']);
    	if(isset($_FILES['attach_cv']['name'])){
    		$file_name = $_FILES['attach_cv']['name'];
    		//echo $file_name;
      		$file_size =$_FILES['attach_cv']['size'];
      		$file_tmp =$_FILES['attach_cv']['tmp_name'];
      		$file_type=$_FILES['attach_cv']['type'];
      		//$file_ext=strtolower(end(explode('.',$file_name)));
      		$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
      		//echo $file_name;
      		$extensions= array("pdf","doc","docx");
      		
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
    		
    		$destinationPath = 'uploads/cv/';
    		$uploadfile = $destinationPath . basename(time()."_".$_FILES['attach_cv']['name']);
    		//echo $uploadfile ;
    		if (move_uploaded_file($_FILES['attach_cv']['tmp_name'], $uploadfile )) {
    			$applicant->attach_cv = time()."_".$_FILES['attach_cv']['name'];    			
    			$last_id = $applicant->id;
    			$applicant->save();
    			Log::info('success cv upload: ');
    			//echo "File is valid, and was successfully uploaded.\n";
			}
    	}
    	return $last_id;
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
    }
    
    public function save_skill_data(){
        $json  = Input::get('hdnSkill');
        Log::info($json); 
        $skillArr = array();

         if(!is_array($json)){
            $skill = json_decode($json,true);
            foreach($skill as $item) { //foreach element in $arr
                $item_arr = array('id'=>Uuid::uuid4()->getHex(),'user_id'=>Auth::user()->id,'type'=>$item['type'],'level'=>$item['level']);
                array_push($skillArr,$item_arr);
            }
        }
        else{
            foreach($json as $item) { //foreach element in $arr
                $item_arr = array('id'=>Uuid::uuid4()->getHex(),'user_id'=>Input::get('user_id'),'type'=>$item['type'],'level'=>$item['level']);
                array_push($skillArr,$item_arr);
            }
        }
        Log::info($skillArr); 
        skill::insert($skillArr); // Eloquent
    }
    
    public function save_experience_data(){
        $json  = Input::get('hdnExp');
        Log::info($json); 
        $expArr = array();

        if(!is_array($json)){
            $exp = json_decode($json,true);
            foreach($exp as $item) { //foreach element in $arr
                $item_arr = array('id'=>Uuid::uuid4()->getHex(),'user_id'=>Auth::user()->id,'organization'=>$item['organization'],'rank'=>$item['rank'],'start_date'=>$item['start_date'],'end_date'=>$item['end_date']);
                array_push($expArr,$item_arr);
            }
        }
        else{
            foreach($json as $item) { //foreach element in $arr
                $item_arr = array('id'=>Uuid::uuid4()->getHex(),'user_id'=>Input::get('user_id'),'organization'=>$item['organization'],'rank'=>$item['rank'],'start_date'=>$item['start_date'],'end_date'=>$item['end_date']);
                array_push($expArr,$item_arr);
            }
        }
        Log::info($expArr); 
        experience::insert($expArr); // Eloquent

    }

    public function save_refree_data(){
        $json  = Input::get('hdnRefree');
        Log::info($json); 
        $refreeArr = array();

        if(!is_array($json)){
            $refree = json_decode($json,true);
            foreach($refree as $item) { //foreach element in $arr
                $item_arr = array('id'=>Uuid::uuid4()->getHex(),'applicant_id'=>Auth::user()->id,'first_name'=>$item['first_name'], 'last_name'=>$item['last_name'], 'organization'=>$item['company'],'rank'=>$item['rank'],'email'=>$item['email'],'mobile_no'=>$item['mobile_no']);
            array_push($refreeArr,$item_arr);
            }
        }
        else{
            foreach($json as $item) { //foreach element in $arr
                $item_arr = array('id'=>Uuid::uuid4()->getHex(),'applicant_id'=>Input::get('user_id'),'first_name'=>$item['first_name'], 'last_name'=>$item['last_name'], 'organization'=>$item['company'],'rank'=>$item['rank'],'email'=>$item['email'],'mobile_no'=>$item['mobile_no']);
            array_push($refreeArr,$item_arr);
            }
        }
        Log::info($refreeArr); 
        refree::insert($refreeArr); // Eloquent
    }*/
    
    public function update_data($id){
       
    	$applicant = applicant::findOrFail(decrypt($id));
        $result = $this->save_applicant_data($applicant);
        //$this->upload_picture($applicant);
       // $this->upload_CV($applicant);
        Log::info('update_data result '.$result); 
        Session::flash('flash_message', 'Successfully update profile!');
		return redirect()->back(); 
    }   
    
    public function getAllApplicantCount(){
    	return DB::table('applicants as a')
            ->select(DB::raw('count(*) as jobseeker_count'))
            ->get();       
    }
}