<?php

namespace jobready365\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Log;
use Validator;
use Input;
use Redirect;
use Session;
use jobready365\township;
use jobready365\city;
use DB;

class TownshipController extends Controller
{
	protected $respose;
	
	public function __construct(Response $response)
	{
		$this->response = $response;
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
				'txtTownship'       => 'required'
		);
		$validator = Validator::make(Input::all(), $rules);
		// process the login
		if ($validator->fails()) {
			return Redirect::to('jobcategory')
			->withErrors($validator)
			->withInput(Input::except('txtTownship'));
		} else {
				try{
					$tsp = new township();
					$tsp->township = Input::get('txtTownship');
					$tsp->city_id = Input::get('ddlCity');
					Log::info('township: '.$tsp);
					$tsp->save();
				}catch (\Exception $e){
					Log::info('duplicate township: ');
					$errorCode = $e->errorInfo[1];
					if($errorCode == 1062){
						Session::flash('duplicate_message', 'Duplicate Township! Already Exist');
						return redirect()->back();
				}
			}
			// redirect
			Session::flash('flash_message', 'Successfully created Township!');
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
		$ret = $this->deleteTownshipbyId($id);
		if($ret == 1)
			return Redirect::to(Session::get('lang').'/jobcategory');
		else
			return "error in delete";
	}
	
	// common function //
	public function getAllTownshipCity(){
		return DB::table('townships as tsp')
		->join('cities as c','tsp.city_id','=','c.id')
		->select('tsp.*','c.city')
		->orderBy('tsp.township')->get();
		
	}
		
	public function deleteTownshipbyId($id)
	{
		$tsp = $this->getTownshipbyId($id);
		$ret = 0;
		if($tsp!= null)
			$ret = $tsp->delete();
			return $ret;
	}
	
	public function getTownshipbyId($id){
		return township::find(decrypt($id));
	}
	
}

