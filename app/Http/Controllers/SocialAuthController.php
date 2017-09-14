<?php

namespace jobready365\Http\Controllers;

use jobready365\SocialAccountService;
use Laravel\Socialite\Facades\Socialite; // socialite namespace
class SocialAuthController extends Controller
{
 // redirect function
    public function redirect(){    	
      return Socialite::driver('facebook')->redirect();
    }
    // callback function
    public function callback(SocialAccountService $service){
      	// when facebook call us a with token
    	echo "<br>callback.";
    	print_r($service);
    }
}
