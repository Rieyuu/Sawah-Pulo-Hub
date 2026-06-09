<?php

namespace App\Http\Controllers;

use App\Models\TicketOrder;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Tampilkan Semua Pesanan Tiket (Opsi filter status)
     */
    public function index(Request $request)
    {
        TicketOrder::checkAndCancelExpired();

        $query = TicketOrder::with(['user:id,name,email,whatsapp', 'ticket']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 200,
            'message' => 'All ticket orders retrieved successfully.',
            'data' => $orders
        ], 200);
    }

    /**
     * Tampilkan Detail Pesanan Tiket (Admin view)
     */
    public function show($id)
    {
        $order = TicketOrder::with(['user:id,name,email,whatsapp', 'ticket'])->find($id);

        if (!$order) {
            return response()->json([
                'status' => 404,
                'message' => 'Ticket order not found.',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Ticket order retrieved successfully.',
            'data' => $order
        ], 200);
    }

    /**
     * Setujui Pembayaran Tiket (Approve)
     */
    public function approve($id)
    {
        $order = TicketOrder::with('ticket')->find($id);

        if (!$order) {
            return response()->json([
                'status' => 404,
                'message' => 'Ticket order not found.',
                'data' => null
            ], 404);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'status' => 400,
                'message' => 'Only pending orders can be approved.',
                'data' => null
            ], 400);
        }

        $ticket = $order->ticket;

        // Validasi stok ulang sebelum disetujui
        if ($ticket->stock < $order->quantity) {
            return response()->json([
                'status' => 400,
                'message' => "Cannot approve order. Insufficient ticket stock ({$ticket->stock} remaining).",
                'data' => null
            ], 400);
        }

        // Jalankan transaksi update
        $order->status = 'success';
        $order->expired_at = now()->addDays(7); // Berlaku selama 7 hari
        $order->save();

        // Kurangi stok tiket
        $ticket->stock -= $order->quantity;
        $ticket->save();

        return response()->json([
            'status' => 200,
            'message' => 'Ticket order approved successfully. E-ticket is now active.',
            'data' => $order->load(['user:id,name,email,whatsapp', 'ticket'])
        ], 200);
    }

    /**
     * Tolak Pembayaran Tiket (Reject)
     */
    public function reject(Request $request, $id)
    {
        $order = TicketOrder::find($id);

        if (!$order) {
            return response()->json([
                'status' => 404,
                'message' => 'Ticket order not found.',
                'data' => null
            ], 404);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'status' => 400,
                'message' => 'Only pending orders can be rejected.',
                'data' => null
            ], 400);
        }

        $order->status = 'failed';
        $order->save();

        return response()->json([
            'status' => 200,
            'message' => 'Ticket order payment rejected successfully.',
            'data' => $order->load(['user:id,name,email,whatsapp', 'ticket'])
        ], 200);
    }
}
