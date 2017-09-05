<?php

namespace jobready365\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Log;
use jobready365\applicant;
use jobready365\candidate;

use Session;
use Auth;
use Input;
use Ramsey\Uuid\Uuid;

class CandidateController extends Controller
{
	public function index()
	{
		$candidate = $this->getAllCandidate();
		return view('candidate.index', compact('candidate'));
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store()
	{
		$ret = $this->save_data();
		if($ret != 'fail'){
			$msg = "Successfully send to applicant!";
			Session::flash('flash_message', $msg);
			return redirect()->back();
		}
		else
		{
			$msg = "Fail to contact applicant.!";
			Session::flash('duplicate_message', $msg);
			return redirect()->back();
		}
	}
	
	public function save_data()
	{
		$msg = '';
		try{	Log::info("candidate save job_id: ".Input::get('hjid'));
			$candidate = new candidate;
			$candidate->id = Uuid::uuid4()->getHex();
			$candidate->employer_id = Input::get('heid');
			$candidate->applicant_id = Input::get('haid');
			$candidate->job_id = Input::get('hjid');
			$candidate->contact_info = Input::get('contact_info');
			$candidate->description = Input::get('description');
			Log::info("save candidate data: ".$candidate);
			return $candidate->save();
		}catch (\Exception $e){
			Log::info("save candidate error: ".$e);
			return "fail";
		}
	}
	
	
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$candidate = $this->getCandidateInfo($id);
		
		$company = $this->getCompanybyJob($id);
		Log::info('candidate: '.$candidate);
		$title=array('Candidate List');
		return view('candidate.index', compact('candidate','company', 'title'));
	}
	
	public function getCandidateInfo($id, $encrypt = 1)
	{
		if($encrypt == 1) $id = decrypt($id);
		return DB::table('applications as app')
		->join('applicants as js', 'app.applicant_id', '=', 'js.user_id')
		->join('jobs as j', 'app.job_id','=','j.id')
		->select('app.id','js.*','js.id as js_id','j.id as jid','app.date_apply','js.name','j.job_title','js.photo')
		->where('app.job_id',$id)
		->orderby('app.date_apply','desc')
		->get();
	}
	
	public function getCompanybyJob($id, $encrypt = 1)
	{
		if($encrypt == 1) $id = decrypt($id);
		return DB::table('jobs as j')
		->join('companies as c', 'c.id', '=', 'j.company_id')
		->select('c.*','j.job_title')
		->where('j.id',$id)
		->get();
	}
	
	//common function
	public function getAllCandidate()
	{
		return DB::table('applications as app')
		->join('applicants as js', 'app.applicant_id', '=', 'js.user_id')
		->join('jobs as j', 'app.job_id','=','j.id')
		->select('app.id','js.id as js_id','j.id as jid','app.date_apply','js.name','j.job_title','js.photo')
		->where('j.employer_id',Auth::user()->id)
		->orderby('app.date_apply','desc')
		->get();
	}
	
	public function getAllCandidatebyJob()
	{
		
	}
	
	public function showshortlisted($jid)
	{
		$candidate= $this->shortlisted($jid);
		$company = $this->getCompanybyJob($jid);
		$title=array('0'=>'Shortlisted');
		Log::info('company : '.$company );
		Log::info('shortlisted: '.$candidate);
		return view('candidate.index', compact('candidate','company','title'));
	}
	
	public function shortlisted($jid, $encrypt = 1)
	{
		if($encrypt == 1) $jid= decrypt($jid);
		
		$shortlisted= DB::table('candidates as c')
		->join('applicants as js','c.applicant_id','=','js.user_id')
		->leftjoin('applications as app','c.applicant_id','=','app.applicant_id')
		->leftjoin('jobs as j','app.job_id','=','j.id')
		->where('app.job_id','=',$jid)
		->select('app.id','js.*','js.id as js_id','j.id as jid','app.date_apply','js.name','j.job_title','js.photo')
		->orderBy('c.created_at','desc')
		->get();
		return $shortlisted;
	}
	
	
}
