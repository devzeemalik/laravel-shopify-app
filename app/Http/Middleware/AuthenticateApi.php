<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Facades\Auth;


class AuthenticateApi
{  
    public function handle(Request $request, Closure $next)
    {   
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $get_token = Token::where('token', $token)->first();
        if($get_token){
            // check token is expire.
            $lastUpdated = $get_token->updated_at;
            // Check if the token has expired (last updated more than 30 minutes ago)
            if ($lastUpdated->diffInMinutes() > 30) {
                return response()->json(['error' => 'Token expired'], 401);
            }
            // update time stamp
            $get_token->touch();
            $get_token->save();

            $user = $get_token->user;
            Auth::login($user);
            return $next($request);
        }
        return response()->json([ 'message' => 'Unauthorized.', ], 401);
    }
}
