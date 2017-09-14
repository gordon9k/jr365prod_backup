<?php

namespace jobready365\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Log;
use Redirect;
use Session;
use Auth;
use Input;
use DB;

use jobready365\package_key;

class PackageKeyController extends Controller
{
	protected $respose;
	
	public function __construct(Response $response)
	{
		$this->response = $response;
	}
	
	public function index(){
		return view('package_key.index');
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store()
	{	Log::info('save package_key: ');
			try{
				$key = new package_key();
				$key->key_type = Input::get('key');
				$key->key_code = $this->randomId();
				$key->generate_time = date("Y-m-d h:i:s");
				$key->generate_by = Auth::user()->id;
				$key->expire_time = date('Y-m-d h:i:s', strtotime("+48 hours", strtotime($key->generate_time)));
				Log::info('package_key: '.$key);
				$key->save();
			}catch (\Exception $e){
				Log::info('error in generate key '.$e);
					Session::flash('duplicate_message', 'Error in generate key!');
					return redirect()->back();
			}
			Session::flash('flash_message', 'You buy '.$key->key_type.' package & key is "'. $key->key_code .'"');
			return redirect()->back();
		}
	
	public function randomId(){
		
		
		/*$id = str_random(10);
		$validator = \Validator::make(['id'=>$id],['id'=>'unique:package_keys,key_code']);
		
		if($validator->fails()){
			$this->randomId();
		}
		
		return $id;*/	
		
		$string = "";
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
		//$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

		for($i=0;$i<10;$i++)
			$string.=substr($chars,rand(0,strlen($chars)),1);
		return $string;
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

	public function checkVaildkey($keycode, $pkg){
		Log::info('$keycode'.$keycode);
		$result = DB::table('package_keys')
		->select('package_keys.*')
		->where('package_keys.key_code','=',$keycode)
		->where('package_keys.key_type','=',$pkg)
		->where('package_keys.generate_time','<=',date("Y-m-d h:i:s"))
		->where('package_keys.expire_time','>=',date("Y-m-d h:i:s"))
		->get();
		Log::info('Valid key'.$result);
		return $result;
	}
}

