<?php

namespace App\Http\Middleware;

use Closure;

class AuthVerify
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
        /*if($request->url() != "http://127.0.0.1/great_god/public/login" &&  $request->url() != "http://127.0.0.1/great_god/public" &&  $request->url() != "http://127.0.0.1/great_god/public/signout")
        {
            $result = $request->getSession();
            if($result != null)
            {
                $userData   = $result->get('users');
                if(empty($userData))
                {
                    return redirect("/");
                }
                else
                {
                    //todo

                }
            }
        }*/

        return $next($request);
    }
}
