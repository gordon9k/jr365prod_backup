<?php

/*
 |--------------------------------------------------------------------------
 | API Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register API routes for your application. These
 | routes are loaded by the RouteServiceProvider within a group which
 | is assigned the "api" middleware group. Enjoy building your API!
 |
 */

Route::get('/version', ['uses' => 'ApiController@version']);

Route::post('/login', ['uses' => 'ApiController@authenticate']);
Route::post('/register', ['uses' => 'ApiController@registerUser']);
Route::post('/confirmation', ['uses' => 'ApiController@confirmationUser']);

Route::get('/city',  [ 'uses' => 'ApiController@getAllCity_api']);
Route::get('/township/{cityid?}',  [ 'uses' => 'ApiController@getAllTownship_api']);
Route::get('/type',  [ 'uses' => 'ApiController@getAllJobType_api']);
Route::get('/category',  [ 'uses' => 'ApiController@getAllJobCategory_api']);
Route::get('/industry/{type?}',  [ 'uses' => 'ApiController@getAllJobIndustry_api']); 

Route::post('/getResult', 'ApiController@getAllJobbySearch_api');
Route::get('/getRecentJob', 'ApiController@getRecentJob_api');

//Route::group(['middleware'=>'jwt-auth'], function(){
	Route::get('/jobcat',  [ 'uses' => 'ApiController@getAllJobCategory_api']);
	Route::get('/jobcat/{id}',  [ 'uses' => 'ApiController@getJobCategorybyId_api']);
	
	Route::get('/employer/{id}',  [ 'uses' => 'ApiController@getEmployerbyId_api']);
	Route::put('/employer/{id}',  [ 'uses' => 'ApiController@updateEmployer_api']);
	Route::get('/expired_employer/{uid}',  [ 'uses' => 'ApiController@checkValidEmployerbyUserId_api']);
	Route::post('/upgrade_package',[ 'uses' => 'ApiController@upgrade_package_api']);
	
	Route::get('/all_company/{uid}',  [ 'uses' => 'ApiController@getAllCompanybyUserID_api']);
	Route::get('/company/{id}',  [ 'uses' => 'ApiController@getCompanybyID_api']);
	Route::post('/company',  [ 'uses' => 'ApiController@insertCompany_api']);
	Route::post('/upload_logo/{id?}',  [ 'uses' => 'ApiController@updateLogo_api']);
	Route::put('/company/{id}',  [ 'uses' => 'ApiController@updateCompany_api']);
	Route::delete('/delete_company/{id}',  [ 'uses' => 'ApiController@deleteCompany_api']);
	
	Route::get('/jobseekerCount',  [ 'uses' => 'ApiController@getAllApplicantCount_api']);
	Route::get('/jobseeker/{uid}',  [ 'uses' => 'ApiController@getApplicantbyId_api']);
	Route::put('/jobseeker/{id}',  [ 'uses' => 'ApiController@updateApplicant_api']);
	Route::post('/upload_picture/{id}',  [ 'uses' => 'ApiController@uploadPicture_api']);
	Route::post('/upload_cv/{id}',  [ 'uses' => 'ApiController@uploadCV_api']);
	
	Route::get('/education/{uid}',  [ 'uses' => 'ApiController@getEducationbyUserId_api']);
	Route::post('/education',  [ 'uses' => 'ApiController@insertEducation_api']);
	Route::delete('/education/{id}',  [ 'uses' => 'ApiController@deleteEducation_api']);
	Route::get('/get_education/{id}',  [ 'uses' => 'ApiController@getEducationbyId_api']);
	Route::put('/update_education/{id}',  [ 'uses' => 'ApiController@updateEducationbyId_api']);
	
	Route::get('/qualification/{uid}',  [ 'uses' => 'ApiController@getQualificationbyUserId_api']);
	Route::post('/qualification',  [ 'uses' => 'ApiController@insertQualification_api']);
	Route::delete('/qualification/{id}',  [ 'uses' => 'ApiController@deleteQualification_api']);
	Route::get('/get_qualification/{id}',  [ 'uses' => 'ApiController@getQualificationbyId_api']);
	Route::put('/update_qualification/{id}',  [ 'uses' => 'ApiController@updateQualificationbyId_api']);
	
	Route::get('/skill/{uid}',  [ 'uses' => 'ApiController@getSkillbyUserId_api']);
	Route::post('/skill',  [ 'uses' => 'ApiController@insertSkill_api']);
	Route::delete('/skill/{id}',  [ 'uses' => 'ApiController@deleteSkill_api']);
	Route::get('/get_skill/{id}',  [ 'uses' => 'ApiController@getSkillbyId_api']);
	Route::put('/update_skill/{id}',  [ 'uses' => 'ApiController@updateSkillbyId_api']);
	
	Route::get('/experience/{uid}',  [ 'uses' => 'ApiController@getExperiencebyUserId_api']);
	Route::post('/experience',  [ 'uses' => 'ApiController@insertExperience_api']);
	Route::delete('/experience/{id}',  [ 'uses' => 'ApiController@deleteExperience_api']);
	Route::get('/get_experience/{id}',  [ 'uses' => 'ApiController@getExperiencebyId_api']);
	Route::put('/update_experience/{id}',  [ 'uses' => 'ApiController@updateExperiencebyId_api']);
	
	/*Route::get('/refree/{uid}',  [ 'uses' => 'ApiController@getRefreebyUserId_api']);
	Route::post('/refree',  [ 'uses' => 'ApiController@insertRefree_api']);
	Route::delete('/refree/{id}',  [ 'uses' => 'ApiController@deleteRefree_api']);
	*/
	
	Route::get('/jobCount',  [ 'uses' => 'ApiController@getAllJobCount_api']);
	Route::get('/job',  [ 'uses' => 'ApiController@getAllJob_api']);
	Route::post('/job',  [ 'uses' => 'ApiController@insertJob_api']);
	Route::put('/job/{id}',  [ 'uses' => 'ApiController@updateJob_api']);
	Route::delete('/jobdelete/{id}',  [ 'uses' => 'ApiController@deleteJobbyId_api']);
	Route::get('/job/{id}',  [ 'uses' => 'ApiController@getJobbyId_api']);
	Route::get('/jobbyemployer/{eid}',  [ 'uses' => 'ApiController@getAllJobbyEmployerId_api']);
	
	//Employer Dashboard
	Route::get('/candidate_count/{cid}', 'ApiController@getCandidateCount_api');
	Route::get('/candidate_list/{jid}', 'ApiController@getCandidateList_api');
	Route::get('/view_cv/{id}/{jid?}', 'ApiController@showApplicantbyId_api');
	
	//Employee Dashboard
	Route::get('/getAllJobHistory/{uid}', 'ApiController@getAllJobHistory_api');
	Route::get('/getAllJobOffer/{uid}', 'ApiController@getAllJobOffer_api');
	Route::post('/applyjob',  [ 'uses' => 'ApiController@applyJob_api']);
	
	Route::get('/getFilterJob/{str?}', 'ApiController@getFilterJob_api');
	
	//Route::post('/advancedFilterJob', 'ApiController@advancedFilterJob_api');
	
	Route::get('/getFilterApplicant/{str?}', 'ApiController@getFilterApplicant_api');
	
	Route::post('/candidate', 'ApiController@insertCandidate_api');
	
	Route::get('/shortlisted_count/{cid}', 'ApiController@getShortistedCount_api');
	Route::get('/shortlisted/{jid}', 'ApiController@getShortlisted_api');
	
	Route::get('/getAppointment/{aid}', 'ApiController@getAppointment_api');
	
	Route::GET('/relatedjob/{id}','ApiController@getRelatedJob_api'); //related job by applicant id
//});
