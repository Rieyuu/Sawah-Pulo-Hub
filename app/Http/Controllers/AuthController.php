<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Registrasi Wisatawan Baru
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'whatsapp' => 'required|string|max:20|unique:users,whatsapp',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        // Berikan role 'user' (visitor) secara default
        $userRole = Role::where('slug', 'user')->first();
        if ($userRole) {
            $user->roles()->attach($userRole);
        }

        return response()->json([
            'status' => 201,
            'message' => 'Registration successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'whatsapp' => $user->whatsapp,
                ],
            ],
        ], 201);
    }

    /**
     * Login Wisatawan / Admin
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string', // email atau WhatsApp
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->identifier)
            ->orWhere('whatsapp', $request->identifier)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid credentials',
                'data' => null,
            ], 401);
        }

        if (! $user->is_active) {
            return response()->json([
                'status' => 403,
                'message' => 'Account is inactive',
                'data' => null,
            ], 403);
        }

        // Generate token kustom menggunakan JwtService
        $accessToken = $this->jwtService->generateAccessToken($user);
        $refreshToken = $this->jwtService->generateRefreshToken($user);

        // Simpan token ke database access_tokens
        $this->jwtService->storeTokens($user, $accessToken, $refreshToken);

        return response()->json([
            'status' => 200,
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'whatsapp' => $user->whatsapp,
                    'roles' => $user->roles->pluck('slug')->toArray(),
                    'is_using_default_password' => $user->is_using_default_password,
                ],
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'expires_in' => JwtService::ACCESS_TOKEN_TTL * 60, // konversi menit ke detik
            ],
        ], 200);
    }

    /**
     * Logout & Revoke Token
     */
    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if ($token) {
            $this->jwtService->revokeToken($token);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Logout successful',
            'data' => null,
        ], 200);
    }
}
