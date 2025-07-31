<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use App\Models\QrScan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    /**
     * Employee dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();

        $activeAssignment = Assignment::where('user_id', $user->id)
            ->where('active', true)
            ->with('checklist.items')
            ->first();

        $recentScans = QrScan::where('user_id', $user->id)
            ->with('qrCode')
            ->latest()
            ->take(5)
            ->get();

        $recentSubmissions = Submission::where('user_id', $user->id)
            ->with(['assignment.checklist', 'item'])
            ->latest()
            ->take(5)
            ->get();

        return view('employee.dashboard', compact('activeAssignment', 'recentScans', 'recentSubmissions'));
    }

    /**
     * Show QR scanner page
     */
    public function qrScanner()
    {
        return view('employee.qr-scanner');
    }

    /**
     * Show active tasks
     */
    public function todayTasks()
    {
        $user = auth()->user();

        $assignment = Assignment::where('user_id', $user->id)
            ->where('active', true)
            ->with(['checklist.items', 'submissions'])
            ->first();

        if (!$assignment) {
            return redirect()->route('employee.dashboard')->with('error', 'Aktif görev bulunmuyor.');
        }

        return view('employee.today-tasks', compact('assignment'));
    }

    /**
     * Show task history
     */
    public function taskHistory()
    {
        $user = auth()->user();

        $assignments = Assignment::where('user_id', $user->id)
            ->with(['checklist', 'submissions'])
            ->latest()
            ->paginate(10);

        return view('employee.task-history', compact('assignments'));
    }

    /**
     * Show QR scan history
     */
    public function qrHistory()
    {
        $user = auth()->user();

        $qrScans = QrScan::where('user_id', $user->id)
            ->with('qrCode')
            ->latest()
            ->paginate(15);

        return view('employee.qr-history', compact('qrScans'));
    }
}
