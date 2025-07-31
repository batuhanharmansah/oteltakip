<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\Assignment;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChecklistController extends Controller
{
    /**
     * Show all checklists
     */
    public function index()
    {
        $checklists = Checklist::with(['creator', 'items'])
            ->latest()
            ->paginate(10);

        return view('admin.checklists.index', compact('checklists'));
    }

    /**
     * Show create checklist form
     */
    public function create()
    {
        return view('admin.checklists.create');
    }

    /**
     * Store new checklist
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*' => 'required|string|max:500',
        ]);

        $checklist = Checklist::create([
            'title' => $request->title,
            'created_by' => auth()->id(),
        ]);

        foreach ($request->items as $item) {
            if (!empty(trim($item))) {
                ChecklistItem::create([
                    'checklist_id' => $checklist->id,
                    'content' => trim($item),
                ]);
            }
        }

        return redirect()->route('admin.checklists.index')->with('success', 'Checklist başarıyla oluşturuldu.');
    }

    /**
     * Show edit checklist form
     */
    public function edit(Checklist $checklist)
    {
        $checklist->load('items');
        return view('admin.checklists.edit', compact('checklist'));
    }

    /**
     * Update checklist
     */
    public function update(Request $request, Checklist $checklist)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*' => 'required|string|max:500',
        ]);

        $checklist->update([
            'title' => $request->title,
        ]);

        // Delete existing items
        $checklist->items()->delete();

        // Create new items
        foreach ($request->items as $item) {
            if (!empty(trim($item))) {
                ChecklistItem::create([
                    'checklist_id' => $checklist->id,
                    'content' => trim($item),
                ]);
            }
        }

        return redirect()->route('admin.checklists.index')->with('success', 'Checklist başarıyla güncellendi.');
    }

    /**
     * Delete checklist
     */
    public function destroy(Checklist $checklist)
    {
        $checklist->delete();
        return redirect()->route('admin.checklists.index')->with('success', 'Checklist başarıyla silindi.');
    }

    /**
     * Show assignment form
     */
    public function assignForm()
    {
        $checklists = Checklist::with('items')->get();
        $employees = User::where('role', 'employee')->get();

        return view('admin.checklists.assign', compact('checklists', 'employees'));
    }

    /**
     * Assign checklist to employees
     */
    public function assign(Request $request)
    {
        $request->validate([
            'checklist_id' => 'required|exists:checklists,id',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        foreach ($request->user_ids as $userId) {
            // Check if assignment already exists
            $existingAssignment = Assignment::where('user_id', $userId)
                ->where('checklist_id', $request->checklist_id)
                ->where('active', true)
                ->first();

            if (!$existingAssignment) {
                Assignment::create([
                    'user_id' => $userId,
                    'checklist_id' => $request->checklist_id,
                    'active' => true,
                ]);
            }
        }

        return redirect()->route('admin.checklists.assign')->with('success', 'Görevler başarıyla atandı.');
    }

    /**
     * Show all assignments
     */
    public function assignments()
    {
        $assignments = Assignment::with(['user', 'checklist'])
            ->latest()
            ->paginate(15);

        return view('admin.checklists.assignments', compact('assignments'));
    }
}
