<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminSettingController extends Controller
{
    /**
     * Tampilkan Semua Pengaturan Situs (Key-Value)
     */
    public function index()
    {
        $settings = SiteSetting::with('user:id,name')->get();

        // Bentuk menjadi key-value pair agar mudah diolah Front-end
        $formattedSettings = [];
        foreach ($settings as $setting) {
            $formattedSettings[$setting->key] = [
                'value' => $setting->value,
                'type' => $setting->type,
                'updated_by' => $setting->user ? $setting->user->name : null,
                'updated_at' => $setting->updated_at,
            ];
        }

        return response()->json([
            'status' => 200,
            'message' => 'Site settings retrieved successfully',
            'data' => $formattedSettings,
        ], 200);
    }

    /**
     * Perbarui / Edit Pengaturan Situs (Bulk Update)
     */
    public function update(Request $request)
    {
        // Kumpulkan keys yang dikirim
        $inputs = $request->all();

        // Validasi input dasar
        $rules = [];
        foreach ($inputs as $key => $value) {
            if ($request->hasFile($key)) {
                $rules[$key] = 'image|mimes:jpeg,png,jpg,svg|max:2048';
            } else {
                $rules[$key] = 'nullable|string';
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $updatedCount = 0;

        foreach ($inputs as $key => $value) {
            // Cek apakah key tersebut ada di pengaturan kita
            $setting = SiteSetting::where('key', $key)->first();

            if (! $setting) {
                // Abaikan key yang tidak terdaftar di database
                continue;
            }

            if ($request->hasFile($key)) {
                // Jika input berupa berkas gambar (Structure Image / Site Plan Image)
                if ($setting->value && str_starts_with($setting->value, '/storage/')) {
                    // Hapus gambar lama jika ada
                    $oldPath = str_replace('/storage/', '', $setting->value);
                    Storage::disk('public')->delete($oldPath);
                }

                $path = $request->file($key)->store('settings', 'public');
                $setting->value = '/storage/'.$path;
                $setting->type = 'image';
            } else {
                // Jika input berupa teks / textarea
                $setting->value = $value;
            }

            // user_id otomatis terisi oleh SiteSettingObserver saat save
            $setting->save();
            $updatedCount++;
        }

        return response()->json([
            'status' => 200,
            'message' => "Successfully updated {$updatedCount} settings",
            'data' => null,
        ], 200);
    }
}
