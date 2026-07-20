<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    /**
     * Mengambil statistik laporan untuk dashboard admin
     */
    public function dashboardStats(Request $request)
    {
        // 1. Hitung ringkasan statistik (Hanya untuk order berstatus 'success')
        $totalRevenue = (float) TicketOrder::where('status', 'success')->sum('total_price');
        $ticketsSold = (int) TicketOrder::where('status', 'success')->sum('quantity');
        $totalVisitors = (int) TicketOrder::where('status', 'success')->distinct('user_id')->count('user_id');

        // 2. Data Grafik Penjualan 7 Hari Terakhir (Agnostik DB - Grouping via PHP)
        $ordersLast7Days = TicketOrder::where('status', 'success')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->orderBy('created_at', 'asc')
            ->get();

        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $dateKey = $day->format('Y-m-d');
            $chartData[$dateKey] = [
                'label' => $day->format('d/m'),
                'raw_date' => $dateKey,
                'revenue' => 0.0,
                'tickets' => 0,
            ];
        }

        foreach ($ordersLast7Days as $order) {
            $dateStr = $order->created_at->format('Y-m-d');
            if (isset($chartData[$dateStr])) {
                $chartData[$dateStr]['revenue'] += (float) $order->total_price;
                $chartData[$dateStr]['tickets'] += (int) $order->quantity;
            }
        }
        $chartData = array_values($chartData);

        // 3. Top 5 Tiket Terpopuler
        $popularTickets = TicketOrder::where('status', 'success')
            ->select('ticket_id', DB::raw('SUM(quantity) as sold_count'))
            ->groupBy('ticket_id')
            ->orderByDesc('sold_count')
            ->take(5)
            ->with('ticket')
            ->get();

        // Load detail tiket untuk popularTickets
        $popularTicketsFormatted = $popularTickets->map(function ($item) {
            $ticket = $item->ticket;

            return [
                'title' => $ticket ? $ticket->title : 'Tiket Terhapus',
                'sold' => (int) $item->sold_count,
            ];
        });

        // 4. Daftar 5 Transaksi Terbaru (Semua status untuk memudahkan admin memantau aktivitas baru)
        $recentOrders = TicketOrder::orderBy('created_at', 'desc')
            ->take(5)
            ->with(['user:id,name', 'ticket:id,title'])
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'ticket_code' => $order->ticket_code,
                    'user_name' => $order->user ? $order->user->name : 'Wisatawan',
                    'ticket_title' => $order->ticket ? $order->ticket->title : 'Tiket',
                    'quantity' => $order->quantity,
                    'total_price' => (float) $order->total_price,
                    'status' => $order->status,
                    'created_at' => $order->created_at->toIso8601String(),
                ];
            });

        return response()->json([
            'status' => 200,
            'message' => 'Statistik dashboard berhasil diambil.',
            'data' => [
                'total_revenue' => $totalRevenue,
                'tickets_sold' => $ticketsSold,
                'total_visitors' => $totalVisitors,
                'chart_data' => $chartData,
                'popular_tickets' => $popularTicketsFormatted,
                'recent_orders' => $recentOrders,
                'is_using_default_password' => auth()->user() ? auth()->user()->is_using_default_password : false,
            ],
        ], 200);
    }
}
