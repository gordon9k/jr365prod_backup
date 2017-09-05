<?php

namespace jobready365\Http\Controllers;

use Response;
use Log;
use Redirect;
use Auth;
use Input;
use DB;
use Session;

use jobready365\employer_topup;
use jobready365\employer;

class EmployerTopupController extends Controller
{
	protected $respose;
	
	public function __construct(Response $response)
	{
		$this->response = $response;
	}
	
	public function index(){
		
	}
	
	public function checkUsedKey($keycode){
		
		$result = DB::table('employer_topups')
		->select(DB::raw('count(*) as count'))
		->where('key_code','=',$keycode)
		->get();
		//Log::info('key used'.$result);
		return $result;
	}
	
	public function upgrade_employer_package(){
		return $this->upgrade_package(Input::get('user_id'));
	}
	
	public function checkValidUpgrade($user_id){
		$result = DB::table('employers')
		->select('*')
		->where('user_id','=',$user_id)
		->get();
		
		return $result;
	}
	
	public function upgrade_package($user_id){
		try{
			//DB::beginTransaction();
			$keycode = Input::get('key_code');
			$pkg_type = Input::get('pkg');
			$result= $this->checkValidUpgrade($user_id);
			Log::info('platinum '.$result[0]->package_type);
			if($result[0]->package_type == 'Platinum' && $result[0]->expired_date > date('Y-m-d h:i:s')){
				Log::info('platinum '.$result[0]->package_type);
				return $result[0]->package_type;
			}else if($result[0]->package_type == 'Gold' && $pkg_type== 'Gold' && $result[0]->expired_date > date('Y-m-d h:i:s')){
				Log::info('gold '.$result[0]->package_type);
				return $result[0]->package_type; 
			}
		//Log::info('key used'.$result);
			$used = $this->checkUsedKey($keycode);
			Log::info('used key count'. $used[0]->count);
			if($used[0]->count == 0){
				$key_info = app('jobready365\Http\Controllers\PackageKeyController')->checkVaildkey($keycode, $pkg_type );
				Log::info('count(key_info) '. count($key_info));
				
				$topup_date = date("Y-m-d h:i:s");
				if($pkg_type == 'Gold') 
					$expire_date = date('Y-m-d h:i:s', strtotime("+720 hours", strtotime($topup_date)));
				else
					$expire_date = date('Y-m-d h:i:s', strtotime("+2160 hours", strtotime($topup_date)));	
				
				if(count($key_info) > 0){
					DB::beginTransaction();
					$emp_topup= new employer_topup();
					$emp_topup->key_code = $keycode ;
					$emp_topup->user_id = $user_id;
					$emp_topup->topup_date = $topup_date;
					$emp_topup->expire_date= $expire_date;
					$emp_topup->key_type = $pkg_type;
					
					Log::info('employer_topup: '.$emp_topup);
					$emp_topup->save(); //save in package info
					
					$id= DB::table('employers')->where('user_id', $user_id)->value('id');
					Log::info('employer id: '.$id);
					$employer_obj= employer::findOrFail(trim($id));
					$employer_obj->package_type = $pkg_type;
					$employer_obj->expired_date= $expire_date;
					$employer_obj->save();
					DB::commit();					
					
					$current = date('Y-m-d H:i:s');
					$expire = $employer_obj->expired_date;
					if($expire != '' && $expire  != null){	
						$remain_time = round((strtotime($expire) - strtotime($current)) /3600);
						
						$employer_obj->remain_time = $remain_time > 0 ? $remain_time .' Hr': 'Expire';
					}
					
					return $employer_obj;					
				}
				else{
					return 'invalidkey';
				}
			}
			else{
				return 'usedkey';
			}
			//DB::commit();
		}catch (\Exception $e){
			Log::info('error in upgrade package '. $e);
			//DB::rollback();
			return 'error';
		}
	}
	
	public function store()
	{
		$ret_val = $this->upgrade_package(Auth::user()->id);
		Log::info('upgrade package '. $ret_val);
		if($ret_val == 'Gold') 
			Session::flash('duplicate_message', 'You already bought Gold. Please wait Until Expire');
		else if($ret_val == 'Platinum') 
			Session::flash('duplicate_message', 'You already bought Platinum. Please wait Until Expire');	
		else if($ret_val == 'usedkey') 
			Session::flash('duplicate_message', 'The key you entered is already used.');
		else if($ret_val == 'invalidkey') 
			Session::flash('duplicate_message', 'You selected package & key are not matched.');
		else if($ret_val == 'error') 
			Session::flash('duplicate_message', 'Error on upgrade.');
		else 
			Session::flash('flash_message', 'Successfully upgrade package.');
		return redirect()->back();
	}
	
	public function randomId(){
		
		$id = str_random(10);
		
		$validator = \Validator::make(['id'=>$id],['id'=>'unique:user_keys,key_code']);
		
		if($validator->fails()){
			$this->randomId();
		}
		
		return $id;
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		
	}
	
}

