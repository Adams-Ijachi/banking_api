<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
class ApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Checks if API_KEY Header is given
        $api_key = $request->header('x-api-key');

        if ($api_key == env('API_KEY')) {
            
            return $next($request);
        }
        
        throw new HttpResponseException(response([
                'message' => 'Unrecognized API_KEY'
            ], 404));  
        



        
    }
}
