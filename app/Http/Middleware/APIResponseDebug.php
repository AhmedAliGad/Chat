<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class APIResponseDebug
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response =  $next($request);
        if (! app()->bound('debugbar') || ! app('debugbar')->isEnabled()) {
            return $response;
        }

        if ($response instanceof JsonResponse) {
            $response->setData(array_merge($response->getData(true), [
                '_debugbar' => Arr::only(app('debugbar')->getData(), 'queries')
            ]));
        }
        return $response;
    }
}
