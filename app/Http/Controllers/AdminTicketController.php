<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminTicketController extends Controller
{
    /**
     * Tampilkan Semua Tiket (Termasuk yang di-softdelete jika requested)
     */
    public function index(Request $request)
    {
        $query = Ticket::query();

        if ($request->boolean('with_trashed')) {
            $query->withTrashed();
        }

        $tickets = $query->with('user:id,name')->get();

        return response()->json([
            'status' => 200,
            'message' => 'Tickets retrieved successfully',
            'data' => $tickets
        ], 200);
    }

    /**
     * Tambah Tiket Baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'is_active' => 'nullable|boolean',
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
            $imagePath = $request->file('image')->store('tickets', 'public');
            $imagePath = '/storage/' . $imagePath;
        }

        // user_id otomatis diisi oleh TicketObserver
        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image_path' => $imagePath,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Ticket created successfully',
            'data' => $ticket->load('user:id,name')
        ], 201);
    }

    /**
     * Tampilkan Detail Tiket
     */
    public function show($id)
    {
        $ticket = Ticket::withTrashed()->with('user:id,name')->find($id);

        if (!$ticket) {
            return response()->json([
                'status' => 404,
                'message' => 'Ticket not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Ticket retrieved successfully',
            'data' => $ticket
        ], 200);
    }

    /**
     * Edit / Update Tiket
     */
    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'status' => 404,
                'message' => 'Ticket not found',
                'data' => null
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($ticket->image_path) {
                $oldPath = str_replace('/storage/', '', $ticket->image_path);
                Storage::disk('public')->delete($oldPath);
            }
            $imagePath = $request->file('image')->store('tickets', 'public');
            $ticket->image_path = '/storage/' . $imagePath;
        }

        $ticket->title = $request->title;
        $ticket->description = $request->description;
        $ticket->price = $request->price;
        $ticket->stock = $request->stock;
        $ticket->is_active = $request->boolean('is_active', $ticket->is_active);
        
        // user_id otomatis di-update oleh TicketObserver
        $ticket->save();

        return response()->json([
            'status' => 200,
            'message' => 'Ticket updated successfully',
            'data' => $ticket->load('user:id,name')
        ], 200);
    }

    /**
     * Hapus Tiket (Soft Delete)
     */
    public function destroy($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'status' => 404,
                'message' => 'Ticket not found or already deleted',
                'data' => null
            ], 404);
        }

        $ticket->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Ticket soft deleted successfully',
            'data' => null
        ], 200);
    }

    /**
     * Restore Tiket yang dihapus
     */
    public function restore($id)
    {
        $ticket = Ticket::onlyTrashed()->find($id);

        if (!$ticket) {
            return response()->json([
                'status' => 404,
                'message' => 'Deleted ticket not found',
                'data' => null
            ], 404);
        }

        $ticket->restore();

        return response()->json([
            'status' => 200,
            'message' => 'Ticket restored successfully',
            'data' => $ticket->load('user:id,name')
        ], 200);
    }
}
