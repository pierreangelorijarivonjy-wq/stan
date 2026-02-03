<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Student;
use App\Models\ExamSession;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $query = Result::with(['student', 'examSession']);

        if ($request->session_id) {
            $query->where('exam_session_id', $request->session_id);
        }

        $results = $query->latest()->paginate(50);
        $sessions = ExamSession::all();

        return view('admin.results.index', compact('results', 'sessions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_session_id' => 'required|exists:exam_sessions,id',
            'subject' => 'required|string',
            'grade' => 'required|numeric|min:0|max:20',
        ]);

        Result::create($validated);

        return back()->with('success', 'Résultat enregistré.');
    }

    public function update(Request $request, Result $result)
    {
        $validated = $request->validate([
            'grade' => 'required|numeric|min:0|max:20',
            'reason' => 'required|string'
        ]);

        $result->update([
            'grade' => $validated['grade'],
            'metadata' => array_merge($result->metadata ?? [], [
                'history' => array_merge($result->metadata['history'] ?? [], [
                    [
                        'old_grade' => $result->getOriginal('grade'),
                        'new_grade' => $validated['grade'],
                        'reason' => $validated['reason'],
                        'updated_at' => now(),
                        'updated_by' => auth()->id()
                    ]
                ])
            ])
        ]);

        return back()->with('success', 'Résultat mis à jour.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'exam_session_id' => 'required|exists:exam_sessions,id',
            'file' => 'required|mimes:csv,txt,xlsx'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\ResultImport($request->exam_session_id),
                $request->file('file')
            );
            return back()->with('success', 'Importation des résultats terminée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'importation : ' . $e->getMessage());
        }
    }

    public function publish(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:exam_sessions,id'
        ]);

        $results = Result::where('exam_session_id', $request->session_id)
            ->where('status', 'draft')
            ->get();

        foreach ($results as $result) {
            $result->update(['status' => 'published']);

            // Notification à l'étudiant
            $student = $result->student;
            if ($student && $student->user) {
                $student->user->notify(new \App\Notifications\ResultPublished($result));
            }
        }

        return back()->with('success', count($results) . ' résultats publiés pour cette session.');
    }
}
