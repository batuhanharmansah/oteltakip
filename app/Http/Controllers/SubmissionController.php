<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubmissionController extends Controller
{
    /**
     * Store task submissions with photos
     */
    public function store(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'photo_data' => 'required|array',
            'photo_data.*' => 'required|string',
            'notes' => 'nullable|array',
        ]);

        $user = auth()->user();
        $assignment = Assignment::findOrFail($request->assignment_id);

        // Check if user owns this assignment
        if ($assignment->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Bu göreve erişim yetkiniz yok.');
        }

        // Check if assignment is active
        if (!$assignment->active) {
            return redirect()->back()->with('error', 'Bu görev aktif değil.');
        }

        // Store submissions with photos
        foreach ($request->photo_data as $itemId => $photoData) {
            // Save photo from base64
            $photoPath = $this->savePhoto($photoData, $user->id, $itemId);

            // Store submission
            Submission::updateOrCreate(
                [
                    'assignment_id' => $assignment->id,
                    'item_id' => $itemId,
                    'user_id' => $user->id,
                ],
                [
                    'is_checked' => true,
                    'photo_path' => $photoPath,
                    'notes' => $request->notes[$itemId] ?? null,
                    'completed_at' => now(),
                ]
            );
        }

        return redirect()->route('employee.dashboard')->with('success', 'Görevler fotoğraflarla birlikte başarıyla kaydedildi.');
    }

    /**
     * Save photo from base64 data
     */
    private function savePhoto($base64Data, $userId, $itemId)
    {
        try {
            // Extract data from base64 data URL
            $data = substr($base64Data, strpos($base64Data, ',') + 1);
            $data = base64_decode($data);

            // Create filename
            $filename = 'task_photo_' . $userId . '_' . $itemId . '_' . time() . '.jpg';
            $path = 'task_photos/' . $filename;

            // Ensure directory exists
            \Storage::disk('public')->makeDirectory('task_photos');

            // Save to storage
            \Storage::disk('public')->put($path, $data);

            // Verify file was saved
            if (!\Storage::disk('public')->exists($path)) {
                throw new \Exception('File was not saved successfully');
            }

            \Log::info('Photo saved successfully: ' . $path . ' (size: ' . strlen($data) . ' bytes)');
            return $path;
        } catch (\Exception $e) {
            \Log::error('Photo save error: ' . $e->getMessage());
            \Log::error('Photo data length: ' . strlen($base64Data));
            throw $e;
        }
    }

    /**
     * Show submission details
     */
    public function show(Assignment $assignment)
    {
        $user = auth()->user();

        // Check if user owns this assignment
        if ($assignment->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Bu göreve erişim yetkiniz yok.');
        }

        $submissions = Submission::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->with('item')
            ->get();

        return view('employee.submission-details', compact('assignment', 'submissions'));
    }

    /**
     * Show all submissions for admin
     */
    public function index()
    {
        $submissions = Submission::with(['user', 'assignment.checklist', 'item'])
            ->latest()
            ->paginate(20);

        return view('admin.submissions.index', compact('submissions'));
    }

    /**
     * Show submissions by user
     */
    public function byUser($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        $submissions = Submission::where('user_id', $userId)
            ->with(['assignment.checklist', 'item'])
            ->latest()
            ->paginate(20);

        return view('admin.submissions.by-user', compact('user', 'submissions'));
    }
}
