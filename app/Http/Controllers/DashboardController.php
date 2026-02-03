<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Convocation;
use App\Models\BankStatement;
use App\Models\ExamSession;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $data = [];
        $search = $request->query('search');

        if ($user->hasRole('student')) {
            $data = $this->getStudentData($search);
        } elseif ($user->hasRole('comptable')) {
            $data = $this->getComptableData($search);
        } elseif ($user->hasRole('scolarite')) {
            $data = $this->getScolariteData($search);
        } elseif ($user->hasRole('admin') || $user->hasRole('admin_it')) {
            $data = $this->getAdminData($search);
        } else {
            // Fallback
            $data = $this->getAdminData($search);
        }

        return view('dashboard', compact('data', 'search'));
    }

    private function getStudentData($search = null)
    {
        $student = auth()->user()->student;
        $paymentsQuery = auth()->user()->payments()->latest();
        $convocationsQuery = $student ? $student->convocations()->with('examSession')->latest() : collect();

        if ($search) {
            $paymentsQuery->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });

            if ($student) {
                $convocationsQuery = $student->convocations()
                    ->with('examSession')
                    ->whereHas('examSession', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                            ->orWhere('location', 'like', "%{$search}%");
                    })
                    ->latest();
            }
        }

        return [
            'total_payments' => auth()->user()->payments()->count(),
            'paid_amount' => auth()->user()->payments()->where('status', 'paid')->sum('amount'),
            'pending_payments' => auth()->user()->payments()->where('status', 'pending')->count(),
            'convocations_count' => $student ? $student->convocations()->count() : 0,
            'recent_payments' => $paymentsQuery->take(5)->get(),
            'upcoming_convocations' => $student ? $convocationsQuery->take(3)->get() : collect(),
        ];
    }

    private function getComptableData($search = null)
    {
        $paymentsQuery = Payment::with('user')->latest();

        if ($search) {
            $paymentsQuery->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
                ->orWhere('amount', 'like', "%{$search}%")
                ->orWhere('transaction_id', 'like', "%{$search}%");
        }

        return [
            'total_payments' => Payment::count(),
            'total_revenue' => Payment::where('status', 'paid')->sum('amount'),
            'revenue_today' => Payment::where('status', 'paid')->whereDate('paid_at', today())->sum('amount'),
            'revenue_week' => Payment::where('status', 'paid')->where('paid_at', '>=', now()->startOfWeek())->sum('amount'),
            'revenue_month' => Payment::where('status', 'paid')->where('paid_at', '>=', now()->startOfMonth())->sum('amount'),
            'validated_payments_count' => Payment::where('status', 'paid')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'pending_amount' => Payment::where('status', 'pending')->sum('amount'),
            'unmatched_statements' => BankStatement::where('status', 'pending')->count(),
            'anomalies_count' => BankStatement::where('status', 'exception')->count(),
            'match_rate' => $this->getMatchRate(),
            'recent_payments' => $paymentsQuery->take(10)->get(),
            'revenue_by_type' => Payment::where('status', 'paid')
                ->selectRaw('type, SUM(amount) as total')
                ->groupBy('type')
                ->get(),
        ];
    }

    private function getScolariteData($search = null)
    {
        $studentsQuery = Student::where('status', 'active');

        if ($search) {
            $studentsQuery->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        $total_convocations = Convocation::count();

        return [
            'total_students' => Student::where('status', 'active')->count(),
            'eligible_students' => Student::whereHas('user.payments', function ($q) {
                $q->where('status', 'paid');
            })->count(),
            'total_sessions' => ExamSession::count(),
            'active_sessions' => ExamSession::whereIn('status', ['planned', 'ongoing'])->count(),
            'convocations_generated' => $total_convocations,
            'convocations_sent' => Convocation::where('status', 'sent')->count(),
            'convocations_downloaded' => Convocation::where('status', 'downloaded')->count(),
            'convocations_scanned' => Convocation::whereNotNull('scanned_at')->count(),
            'scan_rate' => $total_convocations > 0 ? round((Convocation::whereNotNull('scanned_at')->count() / $total_convocations) * 100, 2) : 0,
            'recent_sessions' => ExamSession::latest()->take(5)->get(),
            'recent_convocations' => Convocation::with(['student', 'examSession'])->latest()->take(10)->get(),
            'search_results' => $search ? $studentsQuery->take(10)->get() : null,
        ];
    }

    private function getAdminData($search = null)
    {
        $activity = $this->getRecentActivity($search);

        return [
            'total_users' => \App\Models\User::count(),
            'users_by_role' => \App\Models\User::with('roles')->get()->groupBy(function ($user) {
                return $user->roles->first()->name ?? 'no_role';
            })->map->count(),
            'total_students' => Student::count(),
            'total_payments' => Payment::count(),
            'total_revenue' => Payment::where('status', 'paid')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'total_convocations' => Convocation::count(),
            'total_sessions' => ExamSession::count(),
            'match_rate' => $this->getMatchRate(),
            'anomalies_count' => BankStatement::where('status', 'exception')->count(),
            'recent_activity' => $activity,
            'system_logs_count' => \App\Models\AuditLog::count(),
        ];
    }

    private function getMatchRate(): float
    {
        $total = BankStatement::count();
        if ($total === 0)
            return 0;

        $matched = BankStatement::where('status', 'matched')->count();
        return round(($matched / $total) * 100, 2);
    }

    private function getRecentActivity($search = null)
    {
        $paymentsQuery = Payment::latest();
        $convocationsQuery = Convocation::latest();

        if ($search) {
            $paymentsQuery->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
            $convocationsQuery->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $payments = $paymentsQuery->take(5)->get()->map(function ($p) {
            return [
                'type' => 'payment',
                'description' => "Paiement de {$p->user->name}",
                'amount' => $p->amount,
                'created_at' => $p->created_at,
            ];
        });

        $convocations = $convocationsQuery->take(5)->get()->map(function ($c) {
            return [
                'type' => 'convocation',
                'description' => "Convocation générée pour {$c->student->first_name} {$c->student->last_name}",
                'created_at' => $c->created_at,
            ];
        });

        return $payments->merge($convocations)->sortByDesc('created_at')->take(10);
    }


}
