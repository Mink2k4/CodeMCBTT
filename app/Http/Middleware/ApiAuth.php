<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuth
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
        // return $next($request);
        $api_token = $request->header('Api-token');
        if(isset($api_token)){
            $user = \App\Models\User::where('api_token', $api_token)->where('domain', env('PARENT_SITE'))->first();
            if(isset($user)){
                $request->user = $user;
                return $next($request);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Api token User not found'
                ]);
            }
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Api token not found'
            ]);
        }
    }
}
