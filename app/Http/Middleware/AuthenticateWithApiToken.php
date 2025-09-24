<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AuthenticateWithApiToken
{
   public function handle(Request $request, Closure $next)
{
    if ($request->getMethod() === "OPTIONS") {
        return response()->json([], 200, [
            'Access-Control-Allow-Origin' => $request->headers->get('Origin') ?? '*',
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Authorization, Content-Type',
        ]);
    }

    $token = $request->bearerToken();

   if (!$token) {
    \Log::warning('❌ No llegó token en la extensión');
    return response()->json(['error' => 'Token no proporcionado'], 401);
}

$user = User::where('api_token', $token)->first();

if (!$user) {
    \Log::warning('❌ Token inválido', ['token' => $token]);
    return response()->json(['error' => 'Token inválido'], 401);
}

auth()->setUser($user);

    $response = $next($request);
    $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin') ?? '*');
    $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
    $response->headers->set('Access-Control-Allow-Headers', 'Authorization, Content-Type');

    return $response;
}

}
