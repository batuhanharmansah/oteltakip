<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\QrScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShiftReportController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $shiftType = $request->get('shift_type', 'all');
        
        $users = User::where('role', 'employee');
        
        if ($shiftType !== 'all') {
            $users = $users->where('shift_type', $shiftType);
        }
        
        $users = $users->get();
        
        $reports = [];
        foreach ($users as $user) {
            $workHours = $user->getWorkHoursForDate($date);
            $scans = $user->getScansForDate($date);
            
            $reports[] = [
                'user' => $user,
                'work_hours' => $workHours,
                'scans' => $scans,
                'total_scans' => $scans->count()
            ];
        }
        
        return view('admin.shift-reports.index', compact('reports', 'date', 'shiftType'));
    }
    
    public function userDetail(Request $request, User $user)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        
        $dates = [];
        $currentDate = Carbon::parse($startDate);
        $endDateObj = Carbon::parse($endDate);
        
        while ($currentDate->lte($endDateObj)) {
            $workHours = $user->getWorkHoursForDate($currentDate->format('Y-m-d'));
            $scans = $user->getScansForDate($currentDate->format('Y-m-d'));
            
            $dates[] = [
                'date' => $currentDate->format('Y-m-d'),
                'day_name' => $currentDate->locale('tr')->dayName,
                'work_hours' => $workHours,
                'scans' => $scans,
                'total_scans' => $scans->count()
            ];
            
            $currentDate->addDay();
        }
        
        // İstatistikler
        $totalWorkDays = collect($dates)->where('work_hours.total_hours', '>', 0)->count();
        $totalWorkHours = collect($dates)->sum('work_hours.total_hours');
        $totalLateDays = collect($dates)->where('work_hours.is_late', true)->count();
        $totalEarlyLeaveDays = collect($dates)->where('work_hours.is_early_leave', true)->count();
        $averageWorkHours = $totalWorkDays > 0 ? round($totalWorkHours / $totalWorkDays, 2) : 0;
        
        $stats = [
            'total_work_days' => $totalWorkDays,
            'total_work_hours' => round($totalWorkHours, 2),
            'average_work_hours' => $averageWorkHours,
            'total_late_days' => $totalLateDays,
            'total_early_leave_days' => $totalEarlyLeaveDays,
            'attendance_rate' => $totalWorkDays > 0 ? round(($totalWorkDays / count($dates)) * 100, 2) : 0
        ];
        
        return view('admin.shift-reports.user-detail', compact('user', 'dates', 'stats', 'startDate', 'endDate'));
    }
    
    public function weeklyReport(Request $request)
    {
        $weekStart = $request->get('week_start', now()->startOfWeek()->format('Y-m-d'));
        $shiftType = $request->get('shift_type', 'all');
        
        $users = User::where('role', 'employee');
        
        if ($shiftType !== 'all') {
            $users = $users->where('shift_type', $shiftType);
        }
        
        $users = $users->get();
        
        $weekData = [];
        $currentDate = Carbon::parse($weekStart);
        
        for ($i = 0; $i < 7; $i++) {
            $date = $currentDate->copy()->addDays($i);
            $dayData = [
                'date' => $date->format('Y-m-d'),
                'day_name' => $date->locale('tr')->dayName,
                'users' => []
            ];
            
            foreach ($users as $user) {
                $workHours = $user->getWorkHoursForDate($date->format('Y-m-d'));
                $dayData['users'][] = [
                    'user' => $user,
                    'work_hours' => $workHours
                ];
            }
            
            $weekData[] = $dayData;
        }
        
        return view('admin.shift-reports.weekly', compact('weekData', 'weekStart', 'shiftType'));
    }
    
    public function monthlyReport(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $shiftType = $request->get('shift_type', 'all');
        
        $users = User::where('role', 'employee');
        
        if ($shiftType !== 'all') {
            $users = $users->where('shift_type', $shiftType);
        }
        
        $users = $users->get();
        
        $monthData = [];
        $currentDate = Carbon::parse($month . '-01');
        $endOfMonth = $currentDate->copy()->endOfMonth();
        
        while ($currentDate->lte($endOfMonth)) {
            $date = $currentDate->copy();
            $dayData = [
                'date' => $date->format('Y-m-d'),
                'day_name' => $date->locale('tr')->dayName,
                'users' => []
            ];
            
            foreach ($users as $user) {
                $workHours = $user->getWorkHoursForDate($date->format('Y-m-d'));
                $dayData['users'][] = [
                    'user' => $user,
                    'work_hours' => $workHours
                ];
            }
            
            $monthData[] = $dayData;
            $currentDate->addDay();
        }
        
        // Aylık özet istatistikler
        $summary = [];
        foreach ($users as $user) {
            $totalWorkDays = 0;
            $totalWorkHours = 0;
            $totalLateDays = 0;
            $totalEarlyLeaveDays = 0;
            
            foreach ($monthData as $day) {
                $userDayData = collect($day['users'])->where('user.id', $user->id)->first();
                if ($userDayData && $userDayData['work_hours']['total_hours'] > 0) {
                    $totalWorkDays++;
                    $totalWorkHours += $userDayData['work_hours']['total_hours'];
                    if ($userDayData['work_hours']['is_late']) $totalLateDays++;
                    if ($userDayData['work_hours']['is_early_leave']) $totalEarlyLeaveDays++;
                }
            }
            
            $summary[] = [
                'user' => $user,
                'total_work_days' => $totalWorkDays,
                'total_work_hours' => round($totalWorkHours, 2),
                'average_work_hours' => $totalWorkDays > 0 ? round($totalWorkHours / $totalWorkDays, 2) : 0,
                'total_late_days' => $totalLateDays,
                'total_early_leave_days' => $totalEarlyLeaveDays,
                'attendance_rate' => count($monthData) > 0 ? round(($totalWorkDays / count($monthData)) * 100, 2) : 0
            ];
        }
        
        return view('admin.shift-reports.monthly', compact('monthData', 'summary', 'month', 'shiftType'));
    }
}
