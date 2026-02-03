<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use Illuminate\Http\Request;

class ExamSessionController extends Controller
{
    public function index()
    {
        $sessions = ExamSession::latest()->paginate(20);
        return view('admin.sessions.index', compact('sessions'));
    }

    public function create()
    {
        return view('admin.sessions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'center' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'room' => 'required|string',
            'rules' => 'nullable|array',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
        ]);

        ExamSession::create($validated);

        return redirect()->route('admin.sessions.index')->with('success', 'Session créée avec succès.');
    }

    public function show(ExamSession $session)
    {
        $session->load('convocations.student');
        return view('admin.sessions.show', compact('session'));
    }

    public function edit(ExamSession $session)
    {
        return view('admin.sessions.edit', compact('session'));
    }

    public function update(Request $request, ExamSession $session)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'center' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'room' => 'required|string',
            'rules' => 'nullable|array',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
            'justification' => 'required_if:status,cancelled|string|nullable',
        ]);

        if ($request->status === 'cancelled' && $session->status !== 'cancelled') {
            $validated['metadata'] = array_merge($session->metadata ?? [], [
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
                'justification' => $request->justification
            ]);
        }

        $session->update($validated);

        return redirect()->route('admin.sessions.index')->with('success', 'Session mise à jour.');
    }

    public function destroy(ExamSession $session)
    {
        if ($session->convocations()->exists()) {
            return back()->with('error', 'Impossible de supprimer une session ayant des convocations.');
        }

        $session->delete();
        return redirect()->route('admin.sessions.index')->with('success', 'Session supprimée.');
    }
}
