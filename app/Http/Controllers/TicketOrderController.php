<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\Ticket;
use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TicketOrderController extends Controller
{
    /**
     * Buat Pesanan Tiket Baru (Store Order)
     */
    public function store(Request $request)
    {
        // Rintangi akun admin melakukan checkout tiket wisata
        if (auth()->user() && auth()->user()->isAdmin()) {
            return response()->json([
                'status' => 403,
                'message' => 'Akun administrator tidak diperkenankan untuk melakukan pemesanan tiket wisata. Silakan gunakan akun wisatawan biasa.',
                'data' => null,
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'ticket_id' => 'required|exists:tickets,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $ticket = Ticket::find($request->ticket_id);

        // Cek keaktifan tiket
        if (! $ticket->is_active) {
            return response()->json([
                'status' => 400,
                'message' => 'Ticket is currently inactive and cannot be ordered.',
                'data' => null,
            ], 400);
        }

        // Cek stok tiket
        if ($ticket->stock < $request->quantity) {
            return response()->json([
                'status' => 400,
                'message' => "Insufficient stock. Only {$ticket->stock} tickets available.",
                'data' => null,
            ], 400);
        }

        // Generate kode tiket unik: SWP-XXXXXX
        do {
            $ticketCode = 'SWP-'.strtoupper(Str::random(8));
        } while (TicketOrder::where('ticket_code', $ticketCode)->exists());

        $totalPrice = $ticket->price * $request->quantity;

        $order = TicketOrder::create([
            'user_id' => auth()->id(),
            'ticket_id' => $ticket->id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'ticket_code' => $ticketCode,
            'status' => 'pending_payment',
            'is_used' => false,
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Ticket order created successfully. Please upload proof of payment.',
            'data' => $order->load('ticket'),
        ], 201);
    }

    /**
     * Upload Bukti Pembayaran
     */
    public function uploadPayment(Request $request, $id)
    {
        $order = TicketOrder::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (! $order) {
            return response()->json([
                'status' => 404,
                'message' => 'Ticket order not found.',
                'data' => null,
            ], 404);
        }

        // Hanya izinkan upload jika status masih pending_payment
        if ($order->status !== 'pending_payment') {
            return response()->json([
                'status' => 400,
                'message' => 'Cannot upload payment proof for this order status.',
                'data' => null,
            ], 400);
        }

        // Jalankan check expired untuk order ini
        $timeoutHours = (int) SiteSetting::getValue('payment_timeout_hours', 2);
        if ($order->created_at->addHours($timeoutHours)->isPast()) {
            $order->status = 'failed';
            $order->save();

            return response()->json([
                'status' => 400,
                'message' => 'The payment window for this ticket order has expired. Please place a new order.',
                'data' => null,
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->hasFile('proof_of_payment')) {
            // Hapus bukti bayar lama jika ada
            if ($order->proof_of_payment) {
                $oldPath = str_replace('/storage/', '', $order->proof_of_payment);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('proof_of_payment')->store('payments', 'public');

            $order->proof_of_payment = '/storage/'.$path;
            $order->status = 'pending'; // Ubah ke status menunggu approval admin
            $order->save();
        }

        return response()->json([
            'status' => 200,
            'message' => 'Payment proof uploaded successfully. Waiting for admin verification.',
            'data' => $order->load('ticket'),
        ], 200);
    }

    /**
     * Riwayat Pembelian Wisatawan
     */
    public function history()
    {
        TicketOrder::checkAndCancelExpired(auth()->id());

        $orders = TicketOrder::where('user_id', auth()->id())
            ->with('ticket')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 200,
            'message' => 'Purchase history retrieved successfully.',
            'data' => $orders,
        ], 200);
    }

    /**
     * Detail Pesanan Wisatawan
     */
    public function show($id)
    {
        TicketOrder::checkAndCancelExpired(auth()->id());

        $order = TicketOrder::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('ticket')
            ->first();

        if (! $order) {
            return response()->json([
                'status' => 404,
                'message' => 'Ticket order not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Ticket order details retrieved successfully.',
            'data' => $order,
        ], 200);
    }

    /**
     * Batalkan Pesanan Tiket (Cancel Order)
     */
    public function cancel($id)
    {
        $order = TicketOrder::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (! $order) {
            return response()->json([
                'status' => 404,
                'message' => 'Ticket order not found.',
                'data' => null,
            ], 404);
        }

        // Hanya izinkan pembatalan jika status masih pending_payment
        if ($order->status !== 'pending_payment') {
            return response()->json([
                'status' => 400,
                'message' => 'Hanya pesanan yang belum dibayar yang dapat dibatalkan.',
                'data' => null,
            ], 400);
        }

        $order->status = 'failed';
        $order->save();

        return response()->json([
            'status' => 200,
            'message' => 'Pesanan tiket berhasil dibatalkan.',
            'data' => $order->load('ticket'),
        ], 200);
    }
}
