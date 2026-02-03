<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // Stats Calculation
        $stats = [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'pending_payment' => Student::whereHas('payments', function ($q) {
                $q->where('status', 'pending');
            })->count(),
            'reconciliation_rate' => 85, // Placeholder or calculated logic
        ];

        $query = Student::with(['user', 'payments', 'courses']);

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                    ->orWhere('last_name', 'like', "%{$request->search}%")
                    ->orWhere('matricule', 'like', "%{$request->search}%");
            });
        }

        // Filter by Status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Allow sorting by specific fields
        if (in_array($sortField, ['first_name', 'matricule', 'created_at', 'status'])) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest();
        }

        $students = $query->paginate(50)->withQueryString();

        return view('admin.students.index', compact('students', 'stats'));
    }

    public function show(Student $student)
    {
        $student->load(['user', 'payments', 'convocations.examSession']);
        return view('admin.students.show', compact('student'));
    }
}
