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
                    'photo_data' => $photoData, // Store base64 data as backup
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

            // For Railway deployment, save directly to public directory
            $publicPath = 'public/task_photos/';
            $fullPath = $publicPath . $filename;

            // Ensure directory exists with error handling
            try {
                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0755, true);
                }

                // Double check directory exists
                if (!is_dir($publicPath)) {
                    throw new \Exception('Failed to create directory: ' . $publicPath);
                }
            } catch (\Exception $e) {
                \Log::error('Directory creation failed: ' . $e->getMessage());
                throw new \Exception('Storage directory could not be created');
            }

            // Save to public directory
            file_put_contents($fullPath, $data);

            // Verify file was saved
            if (!file_exists($fullPath)) {
                throw new \Exception('File was not saved successfully');
            }

            \Log::info('Photo saved successfully: ' . $fullPath . ' (size: ' . strlen($data) . ' bytes)');
            return 'task_photos/' . $filename; // Return relative path for database
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

    /**
     * Show submission details for admin
     */
    public function showSubmission(Submission $submission)
    {
        // Check if user has permission to view this submission
        if (auth()->user()->isAdmin() || auth()->user()->id === $submission->user_id) {
            return view('admin.submissions.show', compact('submission'));
        }

        return redirect()->back()->with('error', 'Bu göreve erişim yetkiniz yok.');
    }
}
