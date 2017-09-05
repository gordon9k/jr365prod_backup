<?php

namespace jobready365\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Log;

class LanguageController extends Controller
{   
// we will create new method
    public function index(Request $request, $locale){
    	app()->setlocale($locale);
        Session::set('lang', $locale);  
        $url = explode("/",Session::get('url'));
        Log::info('url1: '.Session::get('url'));
        Log::info('url: '.count($url));
        if(count($url) > 3){
	        $url[3]=$locale;
	        $redirect = implode("/",$url);
	        return \Redirect::to($redirect);
        }
        else{
        	$redirect = implode("/",$url);
        	//echo $redirect;
        	return \Redirect::to($redirect."/".$locale);
        }
     	return redirect()->back();        
    }
}
