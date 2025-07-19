<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Presence;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $period = $request->get('period', 'daily');

        // Add debugging
        Log::info('Dashboard accessed with period: ' . $period);

        $positionCount = Position::count();
        $userCount = User::count();

        $hadirToday = Presence::whereDate('presence_date', $today)
            ->where('is_permission', 0)
            ->count();
        $izinToday = Presence::whereDate('presence_date', $today)
            ->where('is_permission', 1)
            ->count();
        $presencedUserIds = Presence::whereDate('presence_date', $today)->pluck('user_id');
        $notYetAbsenUsers = User::whereNotIn('id', $presencedUserIds)->get();
        
        // Grafik kehadiran
        $attendanceChart = $this->getAttendanceChart($period);
        
        // Grafik pie chart untuk hari ini
        $todayChart = $this->getTodayChart($today, $userCount, $hadirToday, $izinToday);
        
        // Grafik bulanan
        $monthlyChart = $this->getMonthlyAttendanceChart($today);

        // Debug chart data
        Log::info('Chart data generated:', [
            'todayChart' => $todayChart,
            'attendanceChart' => $attendanceChart,
            'monthlyChart' => $monthlyChart
        ]);

        return view('dashboard.index', compact(
            'positionCount',
            'userCount',
            'hadirToday',
            'izinToday',
            'notYetAbsenUsers',
            'attendanceChart',
            'todayChart',
            'monthlyChart',
            'period'
        ));
    }

    private function getTodayChart($today, $userCount, $hadirToday, $izinToday)
    {
        // Fix: Pastikan alpha tidak negatif
        $alphaToday = max(0, $userCount - ($hadirToday + $izinToday));
        
        return [
            'labels' => ['Hadir', 'Izin', 'Alpha'],
            'data' => [$hadirToday, $izinToday, $alphaToday],
            'colors' => ['#28a745', '#ffc107', '#dc3545'],
            'title' => 'Kehadiran Hari Ini'
        ];
    }

    private function getMonthlyAttendanceChart($today)
    {
        $labels = [];
        $hadirCounts = [];
        $izinCounts = [];
        $alphaCounts = [];

        // Data 12 bulan terakhir untuk konsistensi dengan view
        for ($i = 11; $i >= 0; $i--) {
            $monthDate = $today->copy()->subMonths($i);
            $monthStart = $monthDate->copy()->startOfMonth();
            $monthEnd = $monthDate->copy()->endOfMonth();

            $labels[] = $monthDate->format('M Y');

            $hadirCount = Presence::whereBetween('presence_date', [$monthStart, $monthEnd])
                ->where('is_permission', 0)
                ->count();

            $izinCount = Presence::whereBetween('presence_date', [$monthStart, $monthEnd])
                ->where('is_permission', 1)
                ->count();

            // Hitung total hari kerja dalam bulan (skip weekends)
            $workDays = $this->getWorkDaysInMonth($monthStart, $monthEnd);
            $totalExpected = User::count() * $workDays;
            $totalPresent = $hadirCount + $izinCount;
            $alphaCount = max(0, $totalExpected - $totalPresent);

            $hadirCounts[] = (int) $hadirCount;
            $izinCounts[] = (int) $izinCount;
            $alphaCounts[] = (int) $alphaCount;
        }

        return [
            'labels' => $labels,
            'hadir' => $hadirCounts,
            'izin' => $izinCounts,
            'alpha' => $alphaCounts,
            'title' => 'Tren Kehadiran 12 Bulan Terakhir'
        ];
    }

    private function getAttendanceChart($period)
    {
        $today = Carbon::today();

        switch ($period) {
            case 'weekly':
                return $this->getWeeklyChart($today);
            case 'monthly':
                return $this->getMonthlyChart($today);
            case 'daily':
            default:
                return $this->getDailyChart($today);
        }
    }

    private function getDailyChart($today)
    {
        $labels = [];
        $hadirCounts = [];
        $izinCounts = [];
        $alphaCounts = [];

        $allUsers = User::count(); // Simplified

        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $labels[] = $date->format('d M');

            // Skip weekends for more accurate data
            if ($date->isWeekend()) {
                $hadirCounts[] = 0;
                $izinCounts[] = 0;
                $alphaCounts[] = 0;
                continue;
            }

            $hadirCount = Presence::whereDate('presence_date', $date)
                ->where('is_permission', 0)
                ->count();

            $izinCount = Presence::whereDate('presence_date', $date)
                ->where('is_permission', 1)
                ->count();

            $alphaCount = max(0, $allUsers - ($hadirCount + $izinCount));

            $hadirCounts[] = $hadirCount;
            $izinCounts[] = $izinCount;
            $alphaCounts[] = $alphaCount;
        }

        return [
            'labels' => $labels,
            'hadir' => $hadirCounts,
            'izin' => $izinCounts,
            'alpha' => $alphaCounts,
            'title' => 'Kehadiran Harian (7 Hari Terakhir)'
        ];
    }

    private function getWeeklyChart($today)
    {
        $labels = [];
        $hadirCounts = [];
        $izinCounts = [];
        $alphaCounts = [];

        for ($i = 3; $i >= 0; $i--) {
            $weekStart = $today->copy()->subWeeks($i)->startOfWeek();
            $weekEnd = $today->copy()->subWeeks($i)->endOfWeek();

            $labels[] = $weekStart->format('d M') . ' - ' . $weekEnd->format('d M');

            $hadirCount = Presence::whereBetween('presence_date', [$weekStart, $weekEnd])
                ->where('is_permission', 0)
                ->count();

            $izinCount = Presence::whereBetween('presence_date', [$weekStart, $weekEnd])
                ->where('is_permission', 1)
                ->count();

            $workDays = 5; // Mon-Fri
            $totalExpected = User::count() * $workDays;
            $totalPresent = $hadirCount + $izinCount;
            $alphaCount = max(0, $totalExpected - $totalPresent);

            $hadirCounts[] = (int) $hadirCount;
            $izinCounts[] = (int) $izinCount;
            $alphaCounts[] = (int) $alphaCount;
        }

        return [
            'labels' => $labels,
            'hadir' => $hadirCounts,
            'izin' => $izinCounts,
            'alpha' => $alphaCounts,
            'title' => 'Kehadiran Mingguan (4 Minggu Terakhir)'
        ];
    }

    private function getMonthlyChart($today)
    {
        $labels = [];
        $hadirCounts = [];
        $izinCounts = [];
        $alphaCounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = $today->copy()->subMonths($i);
            $monthStart = $monthDate->copy()->startOfMonth();
            $monthEnd = $monthDate->copy()->endOfMonth();

            $labels[] = $monthDate->format('M Y');

            $hadirCount = Presence::whereBetween('presence_date', [$monthStart, $monthEnd])
                ->where('is_permission', 0)
                ->count();

            $izinCount = Presence::whereBetween('presence_date', [$monthStart, $monthEnd])
                ->where('is_permission', 1)
                ->count();

            $workDays = $this->getWorkDaysInMonth($monthStart, $monthEnd);
            $totalExpected = User::count() * $workDays;
            $totalPresent = $hadirCount + $izinCount;
            $alphaCount = max(0, $totalExpected - $totalPresent);

            $hadirCounts[] = (int) $hadirCount;
            $izinCounts[] = (int) $izinCount;
            $alphaCounts[] = (int) $alphaCount;
        }

        return [
            'labels' => $labels,
            'hadir' => $hadirCounts,
            'izin' => $izinCounts,
            'alpha' => $alphaCounts,
            'title' => 'Kehadiran Bulanan (6 Bulan Terakhir)'
        ];
    }

    /**
     * Calculate work days in a month (excluding weekends)
     */
    private function getWorkDaysInMonth($startDate, $endDate)
    {
        $workDays = 0;
        $current = $startDate->copy();
        
        while ($current->lte($endDate)) {
            if (!$current->isWeekend()) {
                $workDays++;
            }
            $current->addDay();
        }
        
        return $workDays;
    }
}