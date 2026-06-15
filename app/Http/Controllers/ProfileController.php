<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Tampilkan Detail Profil Wisatawan
     */
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => 200,
            'message' => 'Profile retrieved successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'whatsapp' => $user->whatsapp,
                    'is_active' => $user->is_active,
                    'is_using_default_password' => $user->is_using_default_password,
                ],
            ],
        ], 200);
    }

    /**
     * Perbarui Profil Wisatawan
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'whatsapp' => 'required|string|max:20|unique:users,whatsapp,'.$user->id,
            'current_password' => 'required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Jika user ingin mengubah password
        if ($request->filled('password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Validation error',
                    'errors' => [
                        'current_password' => ['Password lama yang Anda masukkan tidak sesuai.'],
                    ],
                ], 422);
            }
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->whatsapp = $request->whatsapp;
        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'whatsapp' => $user->whatsapp,
                    'is_using_default_password' => $user->is_using_default_password,
                ],
            ],
        ], 200);
    }

    /**
     * Wisatawan Menghapus Akun Sendiri (Soft Delete)
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        // Cabut semua token user
        $user->accessTokens()->update(['revoked_at' => now()]);

        // Soft-delete akun user
        $user->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Account deleted successfully',
            'data' => null,
        ], 200);
    }
}
