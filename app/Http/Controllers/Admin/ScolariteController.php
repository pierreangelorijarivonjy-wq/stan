<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use App\Models\Convocation;
use App\Models\Student;
use App\Models\Result;
use Illuminate\Http\Request;

class ScolariteController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_sessions' => ExamSession::count(),
            'active_sessions' => ExamSession::whereIn('status', ['planned', 'ongoing'])->count(),
            'total_convocations' => Convocation::count(),
            'sent_convocations' => Convocation::where('status', 'sent')->count(),
            'downloaded_convocations' => Convocation::where('status', 'downloaded')->count(),
            'scanned_convocations' => Convocation::whereNotNull('scanned_at')->count(),
            'eligible_students' => Student::whereHas('user.payments', function ($q) {
                $q->where('status', 'paid');
            })->count(),
        ];

        $recent_sessions = ExamSession::latest()->take(5)->get();
        $recent_convocations = Convocation::with(['student', 'examSession'])->latest()->take(10)->get();

        return view('admin.scolarite.dashboard', compact('stats', 'recent_sessions', 'recent_convocations'));
    }
}