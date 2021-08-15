<?php

namespace App\Http\Middleware;

use App\Models\MobileApp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('authorization')) {
            global $user;
            $token =trim(Str::replaceFirst('Bearer','',$request->header('authorization')));
            $user = MobileApp::where('token', $token)->first();
            if ($user)
                return $next($request);
        }
        return response([
            'status' => false,
            'error' => 'token_error',
            'msg' => ''
        ], 403);
    }
}
