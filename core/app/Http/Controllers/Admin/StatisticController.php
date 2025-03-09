<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Donation;
use Illuminate\Http\Request;

class StatisticController extends Controller
{

    public function statistics()
    {
        $pageTitle = "Donation Statistics";
        return view('admin.donation.statistics', compact('pageTitle'));
    }

    public function getStatistics(Request $request)
    {
        $chartData = [];
        $totalAmount = 0;

        if ($request->time == 'month') {
            foreach (getDaysOfMonth() as $day) {
                $dailyDonations = Donation::success()
                    ->whereYear('created_at', now())
                    ->whereMonth('created_at', now())
                    ->whereDay('created_at', $day)
                    ->selectRaw('DATE(created_at) as date, count(*) as count, SUM(amount) as total')
                    ->groupBy('date')
                    ->first();

                $statusData['success'] = $dailyDonations->count ?? 0;
                $chartData[$day] = $statusData;
                $totalAmount += $dailyDonations->total ?? 0;
            }
        }

        if ($request->time == 'week') {
            $startOfWeek = now()->startOfWeek()->toDateTimeString();
            $endOfWeek = now()->endOfWeek()->toDateTimeString();
            foreach (weekDays() as $day) {
                $dailyDonations = Donation::success()
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->whereDay('created_at', dayNameToDate($day))
                    ->selectRaw('DATE(created_at) as date, count(*) as count, SUM(amount) as total')
                    ->groupBy('date')
                    ->first();
                $statusData['success'] = $dailyDonations->count ?? 0;
                $chartData[$day] = $statusData;
                $totalAmount += $dailyDonations->total ?? 0;
            }
        }

        if ($request->time == 'year') {
            foreach (months() as $month) {
                $parsedMonth = Carbon::parse("1 $month");
                $monthlyDonations = Donation::success()
                    ->whereYear('created_at', now())
                    ->whereMonth('created_at', $parsedMonth->month)
                    ->selectRaw('MONTH(created_at) as month, count(*) as count, SUM(amount) as total')
                    ->groupBy('month')
                    ->first();
                $statusData['success'] = $monthlyDonations->count ?? 0;
                $chartData[$month] = $statusData;
                $totalAmount += $monthlyDonations->total ?? 0;
            }
        }

        // Return both chart data and the total amount
        return response()->json([
            'chart_data' => $chartData,
            'total_amount' => $totalAmount
        ]);
    }
}
