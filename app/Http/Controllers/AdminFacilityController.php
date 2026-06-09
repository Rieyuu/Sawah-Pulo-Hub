<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminFacilityController extends Controller
{
    /**
     * Tampilkan Semua Fasilitas (Termasuk softdeleted jika requested)
     */
    public function index(Request $request)
    {
        $query = Facility::query();

        if ($request->boolean('with_trashed')) {
            $query->withTrashed();
        }

        $facilities = $query->with('user:id,name')->get();

        return response()->json([
            'status' => 200,
            'message' => 'Facilities retrieved successfully',
            'data' => $facilities
        ], 200);
    }

    /**
     * Tambah Fasilitas Baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('facilities', 'public');
            $imagePath = '/storage/' . $imagePath;
        }

        // Slug & user_id otomatis ditangani (slug via Str::slug, user_id via FacilityObserver)
        $facility = Facility::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(5), // slug unik
            'description' => $request->description,
            'image_path' => $imagePath,
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Facility created successfully',
            'data' => $facility->load('user:id,name')
        ], 201);
    }

    /**
     * Tampilkan Detail Fasilitas
     */
    public function show($id)
    {
        $facility = Facility::withTrashed()->with('user:id,name')->find($id);

        if (!$facility) {
            return response()->json([
                'status' => 404,
                'message' => 'Facility not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Facility retrieved successfully',
            'data' => $facility
        ], 200);
    }

    /**
     * Update Fasilitas
     */
    public function update(Request $request, $id)
    {
        $facility = Facility::find($id);

        if (!$facility) {
            return response()->json([
                'status' => 404,
                'message' => 'Facility not found',
                'data' => null
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($facility->image_path) {
                $oldPath = str_replace('/storage/', '', $facility->image_path);
                Storage::disk('public')->delete($oldPath);
            }
            $imagePath = $request->file('image')->store('facilities', 'public');
            $facility->image_path = '/storage/' . $imagePath;
        }

        $facility->name = $request->name;
        // Hanya update slug jika namanya berubah
        if ($facility->isDirty('name')) {
            $facility->slug = Str::slug($request->name) . '-' . Str::random(5);
        }
        $facility->description = $request->description;
        
        // user_id otomatis diisi oleh FacilityObserver saat save
        $facility->save();

        return response()->json([
            'status' => 200,
            'message' => 'Facility updated successfully',
            'data' => $facility->load('user:id,name')
        ], 200);
    }

    /**
     * Hapus Fasilitas (Soft Delete)
     */
    public function destroy($id)
    {
        $facility = Facility::find($id);

        if (!$facility) {
            return response()->json([
                'status' => 404,
                'message' => 'Facility not found or already deleted',
                'data' => null
            ], 404);
        }

        $facility->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Facility soft deleted successfully',
            'data' => null
        ], 200);
    }

    /**
     * Restore Fasilitas yang dihapus
     */
    public function restore($id)
    {
        $facility = Facility::onlyTrashed()->find($id);

        if (!$facility) {
            return response()->json([
                'status' => 404,
                'message' => 'Deleted facility not found',
                'data' => null
            ], 404);
        }

        $facility->restore();

        return response()->json([
            'status' => 200,
            'message' => 'Facility restored successfully',
            'data' => $facility->load('user:id,name')
        ], 200);
    }
}
