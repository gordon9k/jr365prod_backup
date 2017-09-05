<?php
Route::filter('lang', function() {
	App::setLocale(Route::input('lang'));
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::guest('login');
});

/*
Route::filter('auth.api', function()
{
	return Auth::onceBasic();
});
*/
/*
Route::filter('basic.once', function()
{
	return Auth::onceBasic();
});
*/