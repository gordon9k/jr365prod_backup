<?php

namespace jobready365\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
      /*  \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    	 \jobready365\Http\Middleware\Language::class,
    		*/
    		\jobready365\Http\Middleware\Language::class,
    		\Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    		//\Illuminate\Cookie\Middleware\EncryptCookies::class,
    		//\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    		//\Illuminate\Session\Middleware\StartSession::class,
    		//\Illuminate\View\Middleware\ShareErrorsFromSession::class,
    		//\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \jobready365\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \jobready365\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \jobready365\Http\Middleware\EncryptCookies::class,
            \Illuminate\Session\Middleware\StartSession::class,
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \jobready365\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    	
        'apiauth'=> \jobready365\Http\Middleware\ApiAuthMiddleware::class,
        'jwt-auth' => \jobready365\Http\Middleware\authJWT::class,
        'cors' => \jobready365\Http\Middleware\Cors::class,
    		
    	'lang' => \jobready365\Http\Middleware\Language::class,
    ];
}
