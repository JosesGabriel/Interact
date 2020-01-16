<?php

namespace App\Http\Middleware;

use Closure;

class ProfileResponse
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
        $response = $next($request);

        if (
            app()->bound('debugbar') &&
            app('debugbar')->isEnabled()
        ){
            $original = $response->getOriginalContent();
            $original['_debugbar'] = app('debugbar')->getData();

            $response->setContent(json_encode($original));
        }

        return $response;
    }
}
