<?php 
namespace jobready365\Http\Middleware;

use Closure;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
//use Illuminate\Contracts\Routing\Middleware;

class Language
{   
 	public function __construct(Application $app, Redirector $redirector, Request $request) {
        $this->app = $app;
        $this->redirector = $redirector;
        $this->request = $request;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
 	public function handle($request, Closure $next)
    {
        // Make sure current locale exists.
       /* $locale = $request->segment(1);
       
        if ( ! array_key_exists($locale, $this->app->config->get('app.locales'))) {
        //if(in_array($locale, $this->app->config->get('app.locale'))){
            $segments = $request->segments();
            $segments[0] = $this->app->config->get('app.fallback_locale');
            return $this->redirector->to(implode('/', $segments));
        }

        $this->app->setLocale($locale);

        return $next($request);*/
    	
    	if(!\Session::has('lang'))
    	{
    		\Session::put('lang', \Config::get('app.locale'));
    	}
    	
    	app()->setLocale(\Session::get('lang'));
    	
    	return $next($request);
    }
}