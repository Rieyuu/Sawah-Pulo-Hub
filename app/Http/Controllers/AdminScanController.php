<?php

namespace App\Http\Controllers;

use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminScanController extends Controller
{
    /**
     * Scan dan verifikasi tiket masuk wisatawan
     */
    public function scan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_code' => 'required|string',
        ], [
            'ticket_code.required' => 'Kode tiket harus diisi.',
            'ticket_code.string' => 'Kode tiket harus berupa teks.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $ticketCode = $request->input('ticket_code');

        // Cari pesanan tiket berdasarkan kode unik
        $order = TicketOrder::where('ticket_code', $ticketCode)->first();

        if (! $order) {
            return response()->json([
                'status' => 404,
                'message' => 'Tiket tidak terdaftar/ditemukan.',
                'data' => null,
            ], 404);
        }

        // 1. Validasi Status Pembayaran
        if ($order->status !== 'success') {
            if ($order->status === 'pending_payment') {
                return response()->json([
                    'status' => 400,
                    'message' => 'Tiket belum dibayar.',
                    'data' => null,
                ], 400);
            }

            if ($order->status === 'pending') {
                return response()->json([
                    'status' => 400,
                    'message' => 'Tiket sedang menunggu verifikasi pembayaran oleh admin.',
                    'data' => null,
                ], 400);
            }

            if ($order->status === 'failed') {
                return response()->json([
                    'status' => 400,
                    'message' => 'Tiket tidak dapat digunakan karena pembayaran gagal atau kedaluwarsa.',
                    'data' => null,
                ], 400);
            }

            return response()->json([
                'status' => 400,
                'message' => 'Tiket tidak valid atau pembayaran belum disetujui.',
                'data' => null,
            ], 400);
        }

        // 2. Validasi Kedaluwarsa tiket
        if ($order->expired_at && $order->expired_at->isPast()) {
            return response()->json([
                'status' => 400,
                'message' => 'Tiket sudah kedaluwarsa pada '.$order->expired_at->format('d-m-Y H:i').'.',
                'data' => null,
            ], 400);
        }

        // 3. Validasi Double Scanning (Penggunaan Ulang)
        if ($order->is_used) {
            $formattedUsedAt = $order->used_at ? $order->used_at->format('d-m-Y H:i') : '-';

            return response()->json([
                'status' => 400,
                'message' => 'Tiket sudah pernah digunakan pada '.$formattedUsedAt.'.',
                'data' => null,
            ], 400);
        }

        // Jalankan update status tiket digunakan
        $order->is_used = true;
        $order->used_at = now();
        $order->save();

        return response()->json([
            'status' => 200,
            'message' => 'Tiket berhasil discan. Selamat menikmati kunjungan Anda!',
            'data' => $order->load(['user:id,name,email,whatsapp', 'ticket:id,title,price']),
        ], 200);
    }
}
