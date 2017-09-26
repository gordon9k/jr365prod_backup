<?php

/*
 |--------------------------------------------------------------------------
 | Web Routes
 |--------------------------------------------------------------------------
 |
 | This file is where you may define all of the routes that are handled
 | by your application. Just tell Laravel the URIs it should respond
 | to using a Closure or controller method. Build something great!
 |
 */

$languages = array('en', 'mm');  //available languages (without the default language)

$locale= Request::segment(1);
if($locale== null) $locale = 'en';

if(in_array($locale, $languages)){
	\App::setLocale($locale);   //to change the lang over the entire app
	Config::set('app.locale', $locale);
	//$lng = $locale;
}else{
	$locale = null;  //no prefix for the default lang
}
Session::set('lang', Config::get('app.locale'));

Route::get('/{lang?}', 'HomeController@index',function ($locale) {
	App::setLocale($locale);
});
	
Route::group(['prefix' => $locale], function (){
	// Route::POST('/tmpRegister', 'Auth\RegisterController@tmpRegister');
	Route::get('register', 'RegistrationController@create');
	Route::post('register', 'RegistrationController@store')->name('storeRegister');
	// Route::group(['middleware' => ['is.active']], function () {
	Route::get('confirm/{phone}', 'RegistrationController@confirm')->name('confirm');
	Route::get('resend/{phone}', 'RegistrationController@resendCode')->name('resendCode');
	// });
	Route::post('confirmation', 'RegistrationController@resendCode')->name('confirmCode');
	
	/**
	 * Authentication Route
	 */
	Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    // Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    /* End Authentication Route */

	// Auth::routes();
	Route::get('getLatestJob', 'JobController@getLatestJob'); //Home Page
	Route::get('getLatestJobbyKeyword', 'JobController@getFilterJob'); //Home Page
	Route::get('/application/{id}', 'ApplicationController@show');
	Route::get('/job/{id}', 'JobController@show'); //show job
	Route::get('category', 'JobCategoryController@getAllJobCategory');
	Route::get('/townshipByCity/{city_id?}', 'HomeController@getAllTownshipbyCity');
	Route::get('getAllFeaturedCompany', 'CompanyController@getAllFeaturedCompany');
	Route::get('/company_job/{cid}', 'JobController@getAllCompanyJobs');
	//Route::get('fbredirect', 'SocialAuthController@redirect');
	//Route::get('fbcallback', 'SocialAuthController@callback');
	Route::get('/category_autocomplete', 'JobCategoryController@category_autocomplete');
	Route::get('/policy', 'HomeController@policy');
	Route::get('/terms', 'HomeController@terms');
	Route::get('/download/android', 'HomeController@androidDownload');
	Route::get('/download/ios', 'HomeController@iosDownload');
});
	
Route::get('/language/{lang}', 'LanguageController@index');
	
Route::group(['middleware'=>'auth','prefix' => $locale], function (){

		Route::GET('/dashboard', 'HomeController@dashboard')->name('dashboard');
		
		Route::GET('/changepassword', function() {return view('auth.passwords.changepassword'); });
		Route::POST('/changepassword', 'Auth\UpdatePasswordController@update');
		//Route::POST('/changepassword', 'Auth\ResetPasswordController@reset');
		Route::GET('/relatedjob','JobController@getRelatedJob'); //show related job
		
		Route::get('/job_industry/{type?}', 'JobIndustryController@getAllJobIndustryList'); //depand on shop or company
		
		Route::resource('/jobindustry', 'JobIndustryController');
		
		Route::resource('/jobcategory', 'JobCategoryController');
		
		Route::resource('/township', 'TownshipController');
		
		Route::resource('/job', 'JobController');			
		Route::get('/jobs/create', 'JobController@create');
		Route::post('/job_activate', 'JobController@job_activate');
		
		Route::resource('/employer', 'EmployerController');
		
		Route::resource('/applicant', 'ApplicantController');
		
		Route::resource('/education', 'EducationController');
		
		Route::resource('/qualification', 'QualificationController');
		
		Route::resource('/skill', 'SkillController');
		
		Route::resource('/experience', 'ExperienceController');
		
		Route::resource('/refree', 'RefreeController');
		
		Route::resource('/application', 'ApplicationController');			
		//Route::post('/application', 'ApplicationController@store');
		
		Route::resource('/candidate', 'CandidateController');
		
		Route::resource('/company', 'CompanyController');
		
		//Route::post('/company/getCompanyId', 'CompanyController@getCompanyId');
		
		Route::get('/job_by_candidate', 'HomeController@getJobByCandidateCount'); //show candidate list -- join application & job
		
		Route::get('/shortlisted_by_candidate', 'HomeController@getShortlistedByCandidateCount'); //show shortlisted -- join candidate & job
		Route::get('/shortlisted/{jid}', 'CandidateController@showshortlisted');
		
		//Route::get('/download_file/{filename}', 'HomeController@download_file');
		//Route::resource('/setting', 'SettingController');
		
		Route::resource('/package_key','PackageKeyController');
		
		Route::resource('/upgrade_package','EmployerTopupController');
		
		Route::get('/view_cv/{id}/{jid?}', 'ApplicantController@view_cv');
		//Route::get('/shortlisted', 'CandidateController@shortlisted');
		
		Route::get('/browse_cv', 'ApplicantController@browse_cv');
		
		Route::get('/get_browse_cv_list', 'ApplicantController@get_browse_cv_list');
		
	});	