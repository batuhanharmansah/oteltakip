<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Assignment;
use App\Models\QrScan;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_employees' => User::where('role', 'employee')->count(),
            'total_assignments' => Assignment::count(),
            'total_submissions' => Submission::count(),
            'total_qr_scans' => QrScan::count(),
        ];

        $recent_assignments = Assignment::with(['user', 'checklist'])
            ->latest()
            ->take(5)
            ->get();

        $recent_submissions = Submission::with(['user', 'assignment.checklist'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_assignments', 'recent_submissions'));
    }

    /**
     * Show all users
     */
    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show create user form
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'role' => ['required', Rule::in(['admin', 'employee'])],
        ]);

        User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'address' => $request->address,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    /**
     * Show edit user form
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'role' => ['required', Rule::in(['admin', 'employee'])],
        ]);

        $user->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'phone' => $request->phone,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'address' => $request->address,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı başarıyla güncellendi.');
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Kendinizi silemezsiniz.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı başarıyla silindi.');
    }

    /**
     * Show user history
     */
    public function userHistory(User $user)
    {
        $assignments = $user->assignments()->with('checklist')->latest()->get();
        $submissions = $user->submissions()->with(['assignment.checklist', 'item'])->latest()->get();
        $qrScans = $user->qrScans()->with('qrCode')->latest()->get();

        return view('admin.users.history', compact('user', 'assignments', 'submissions', 'qrScans'));
    }
}
