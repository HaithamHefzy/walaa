<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Visit;
use App\Models\Feedback;
use App\Traits\ApiResponse;

class ReportsController extends Controller
{
    use ApiResponse;

    /**
     * GET /reports
     * Returns multiple sections of operational data in one JSON response.
     * Adjust the queries to match your actual data and models.
     */
    public function index(Request $request)
    {
        // 1) Daily client/visit report (today)
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd   = Carbon::today()->endOfDay();

        $visitsToday  = Visit::whereBetween('created_at', [$todayStart, $todayEnd])->count();
        $waitingToday = Visit::whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('status', 'waiting')
            ->count();
        $calledToday  = Visit::whereBetween('created_at', [$todayStart, $todayEnd])
            ->where('status', 'called')
            ->count();

        $dailyClientsSection = [
            'total_visits_today' => $visitsToday,
            'waiting_today'      => $waitingToday,
            'called_today'       => $calledToday,
        ];

        // 2) Current customers now (e.g., visits with status='called')
        $currentClientsCount = Visit::where('status', 'called')->count();
        $currentClientsSection = [
            'current_clients_now' => $currentClientsCount,
            // Add more breakdown if needed
        ];

        // 3) Feedback stats
        //    - total feedbacks
        //    - overall average from (food_quality + service_quality + value_for_money) / 3
        $totalFeedbacks = Feedback::count();

        // If each column is on a 1-10 scale, we can compute an overall average:
        $overallAvg = Feedback::selectRaw(
            'AVG((food_quality + service_quality + value_for_money) / 3) as avg_rating'
        )->value('avg_rating');

        $feedbackSection = [
            'total_feedbacks' => $totalFeedbacks,
            'average_rating'  => $overallAvg ? round($overallAvg, 2) : 0, // Round to 2 decimals
        ];

        // 4) Revenue or payments (placeholder, if no POS integration)
        $revenueSection = [
            'today_revenue'     => 0,  // placeholder
            'current_month_rev' => 0,  // placeholder
            'last_month_rev'    => 0,  // placeholder
        ];

        // 5) Build the final data array with each "section" you want to display
        $data = [
            'daily_clients_report'    => $dailyClientsSection,
            'current_clients_report'  => $currentClientsSection,
            'feedback_report'         => $feedbackSection,
            'revenue_report'          => $revenueSection,
        ];

        return $this->successResponse($data, 'Operational reports retrieved successfully');
    }
}
