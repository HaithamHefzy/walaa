<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Visit;
use App\Traits\ApiResponse;

class DashboardController extends Controller
{
    use ApiResponse;

    /**
     * GET /dashboard/stats
     * Returns the same 3 stats (new visits, clients now, waiting clients)
     * for four periods: today, yesterday, this month, last month.
     */
    public function stats(Request $request)
    {
        // 1) Define date ranges

        // Today
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd   = Carbon::today()->endOfDay();

        // Yesterday
        $yesterdayStart = Carbon::yesterday()->startOfDay();
        $yesterdayEnd   = Carbon::yesterday()->endOfDay();

        // This month
        $thisMonthStart = Carbon::now()->startOfMonth();
        $thisMonthEnd   = Carbon::now()->endOfMonth();

        // Last month
        // subMonthNoOverflow() handles edge cases; you can also use subMonth()
        $lastMonthStart = Carbon::now()->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd   = Carbon::now()->subMonthNoOverflow()->endOfMonth();

        // 2) Build a helper function or just do queries inline
        // For clarity, let's do inline queries for each period

        // ------------------
        // STATS FOR TODAY
        // ------------------
        $newVisitsToday = Visit::whereBetween('created_at', [$todayStart, $todayEnd])->count();

        // Example: "clients now" might be visits with status='called'
        $clientsNowToday = Visit::whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('status', 'called')
            ->count();

        // Example: "waiting clients" might be visits with status='waiting'
        $waitingClientsToday = Visit::whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('status', 'waiting')
            ->count();

        // ------------------
        // STATS FOR YESTERDAY
        // ------------------
        $newVisitsYesterday = Visit::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])->count();

        $clientsNowYesterday = Visit::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->where('status', 'called')
            ->count();

        $waitingClientsYesterday = Visit::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->where('status', 'waiting')
            ->count();

        // ------------------
        // STATS FOR THIS MONTH
        // ------------------
        $newVisitsThisMonth = Visit::whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])->count();

        $clientsNowThisMonth = Visit::whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])
            ->where('status', 'called')
            ->count();

        $waitingClientsThisMonth = Visit::whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])
            ->where('status', 'waiting')
            ->count();

        // ------------------
        // STATS FOR LAST MONTH
        // ------------------
        $newVisitsLastMonth = Visit::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

        $clientsNowLastMonth = Visit::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->where('status', 'called')
            ->count();

        $waitingClientsLastMonth = Visit::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->where('status', 'waiting')
            ->count();

        // 3) Build the final response
        $data = [
            'today' => [
                'new_visits'      => $newVisitsToday,
                'clients_now'     => $clientsNowToday,
                'waiting_clients' => $waitingClientsToday,
            ],
            'yesterday' => [
                'new_visits'      => $newVisitsYesterday,
                'clients_now'     => $clientsNowYesterday,
                'waiting_clients' => $waitingClientsYesterday,
            ],
            'this_month' => [
                'new_visits'      => $newVisitsThisMonth,
                'clients_now'     => $clientsNowThisMonth,
                'waiting_clients' => $waitingClientsThisMonth,
            ],
            'last_month' => [
                'new_visits'      => $newVisitsLastMonth,
                'clients_now'     => $clientsNowLastMonth,
                'waiting_clients' => $waitingClientsLastMonth,
            ],
        ];

        return $this->successResponse($data, 'Dashboard statistics retrieved successfully');
    }
}
