<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Presence;
use App\Models\Position;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $period = $request->get('period', 'daily');

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
        $alphaToday = $userCount - ($hadirToday + $izinToday);
        
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

        // Data 6 bulan terakhir
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

            // Hitung hari kerja dalam bulan (asumsi 22 hari kerja)
            $workDays = 22;
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

        $allUsers = User::all();

        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $labels[] = $date->format('d M');

            $activeUsers = $allUsers->filter(function ($user) use ($date) {
                return $user->start_date <= $date &&
                    (!$user->end_date || $user->end_date >= $date);
            })->count();

            $hadirCount = Presence::whereDate('presence_date', $date)
                ->where('is_permission', 0)
                ->count();

            $izinCount = Presence::whereDate('presence_date', $date)
                ->where('is_permission', 1)
                ->count();

            $alphaCount = $activeUsers - ($hadirCount + $izinCount);

            $hadirCounts[] = $hadirCount;
            $izinCounts[] = $izinCount;
            $alphaCounts[] = max(0, $alphaCount);
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

            $workDays = 5;
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

            $workDays = 22;
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
}