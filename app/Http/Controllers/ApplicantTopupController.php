<?php

namespace jobready365\Http\Controllers;

use Response;
use Log;
use Redirect;
use Auth;
use Input;
use DB;
use Session;

use jobready365\applicant_topup;
use jobready365\employer;

use Illuminate\Http\Request;

class ApplicantTopupController extends Controller
{
    protected $respose;
	
	public function __construct(Response $response)
	{
		$this->response = $response;
	}
	
	public function index(){
		
	}
}
