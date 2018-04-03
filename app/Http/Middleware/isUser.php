<?php

namespace App\Http\Middleware;

use Closure;

use JWTAuth;

class isUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(JWTAuth::parseToken()->authenticate()->hasRole('User'))
        {

             return $next($request);

        }//end of if
       
    }//end of function
}//end of class
