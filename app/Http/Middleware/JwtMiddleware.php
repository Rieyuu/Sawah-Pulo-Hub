<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    protected $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized - No token provided',
                'data' => null
            ], 401);
        }

        $user = $this->jwtService->validateToken($token);

        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized - Invalid or expired token',
                'data' => null
            ], 401);
        }

        // Check role if specified
        if ($role && !$user->hasRole($role)) {
            return response()->json([
                'status' => 403,
                'message' => 'Forbidden - Access denied',
                'data' => null
            ], 403);
        }

        // Bind user to request
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
