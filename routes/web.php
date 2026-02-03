<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ConvocationController;
use App\Http\Controllers\ReconciliationController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountSwitcherController;

// ==================== HEALTH CHECK (Docker/Monitoring) ====================
require __DIR__ . '/health.php';

// ==================== PAGE D'ACCUEIL PUBLIQUE ====================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Changement de compte rapide
Route::get('/switch-account', [AccountSwitcherController::class, 'index'])->name('account.switcher');
Route::post('/switch-account', [AccountSwitcherController::class, 'switch'])->name('account.switch');
Route::get('/switch-password', [AccountSwitcherController::class, 'showPasswordForm'])->name('account.switch.password');
Route::post('/switch-password', [AccountSwitcherController::class, 'verifyPassword'])->name('account.switch.verify');



// ==================== COURS (PUBLIC) ====================
Route::get('/courses', function () {
    return view('courses.index');
})->name('courses.index');

Route::get('/courses/{id}', function ($id) {
    return view('courses.show', ['id' => $id]);
})->name('courses.show');

// ==================== AUTH ====================
require __DIR__ . '/auth.php';

// OTP Routes (Accessible during login/registration flow)
Route::get('/verify-otp', [App\Http\Controllers\OtpVerificationController::class, 'show'])->name('otp.show');
Route::post('/verify-otp', [App\Http\Controllers\OtpVerificationController::class, 'verify'])->name('otp.verify');
Route::post('/resend-otp', [App\Http\Controllers\OtpVerificationController::class, 'resend'])->name('otp.resend');

Route::middleware('auth')->group(function () {
    // Other auth routes if any
});

// ==================== ROUTES AUTHENTIFIÉES ====================
Route::middleware(['auth', 'verified', App\Http\Middleware\EnsureOtpVerified::class])->group(function () {
    // Dashboard dynamique selon le rôle
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifications
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');


    // Profil
    Route::middleware(['auth'])->group(function () {
        Route::post('/two-factor/enable', [App\Http\Controllers\Auth\TwoFactorController::class, 'enable'])->name('two-factor.enable');
        Route::post('/two-factor/confirm', [App\Http\Controllers\Auth\TwoFactorController::class, 'confirm'])->name('two-factor.confirm');
        Route::delete('/two-factor/disable', [App\Http\Controllers\Auth\TwoFactorController::class, 'disable'])->name('two-factor.disable');
        Route::post('/two-factor/regenerate', [App\Http\Controllers\Auth\TwoFactorController::class, 'regenerate'])->name('two-factor.regenerate');
        Route::post('/two-factor/recovery-codes', [App\Http\Controllers\Auth\TwoFactorController::class, 'showRecoveryCodes'])->name('two-factor.recovery-codes');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==================== COURS ====================

    // Liste des cours avec recherche
    Route::get('/courses', [App\Http\Controllers\CourseController::class, 'index'])->name('courses');

    // === ROUTES SPÉCIFIQUES (DOIVENT ÊTRE AVANT LA GÉNÉRIQUE) ===
    Route::get('/course/math', fn() => view('courses.math'))->name('course.math');
    Route::get('/course/eco', fn() => view('courses.eco'))->name('course.eco');
    Route::get('/course/info', fn() => view('courses.info'))->name('course.info');

    // === ROUTE GÉNÉRIQUE (APRÈS LES SPÉCIFIQUES) ===
    Route::get('/course/{slug}/lesson/{lesson}/download', [App\Http\Controllers\CourseController::class, 'downloadResource'])->name('course.download');
    Route::get('/course/{slug}', [App\Http\Controllers\CourseController::class, 'show'])->name('course.show');
    Route::post('/course/{slug}/lesson/{lesson}/like', [App\Http\Controllers\CourseController::class, 'toggleLike'])->name('course.like');

    // ==================== LMS V2 (Student) ====================
    Route::middleware('role:student')->prefix('my-courses')->name('student.courses.')->group(function () {
        Route::get('/', [App\Http\Controllers\Student\StudentCourseController::class, 'index'])->name('index');
        Route::get('/{course}', [App\Http\Controllers\Student\StudentCourseController::class, 'show'])->name('show');
        Route::post('/lessons/{lesson}/complete', [App\Http\Controllers\Student\StudentCourseController::class, 'completeLesson'])->name('lessons.complete');
        Route::get('/resources/{resource}/download', [App\Http\Controllers\Student\StudentCourseController::class, 'downloadResource'])->name('resources.download');
        Route::post('/quizzes/{quiz}/start', [App\Http\Controllers\Student\StudentCourseController::class, 'startQuiz'])->name('quizzes.start');
        Route::post('/quiz-attempts/{attempt}/submit', [App\Http\Controllers\Student\StudentCourseController::class, 'submitQuiz'])->name('quizzes.submit');
    });

    // ==================== PAIEMENT ====================
    Route::middleware('role:student|admin')->group(function () {
        Route::get('/payments', fn() => view('payments.index'))->name('payments');
        Route::get('/paiement-securise', fn() => view('payments.choose'))->name('payment.choose');
        Route::post('/payment/initiate', [PaymentController::class, 'initiate'])->name('payment.initiate');

        // Simulation / Waiting Routes
        Route::get('/payment/waiting/{payment}', [PaymentController::class, 'waiting'])->name('payment.waiting');
        Route::get('/payment/check-status/{payment}', [PaymentController::class, 'checkStatus'])->name('payment.check-status');
        Route::post('/payment/simulate-webhook', [PaymentController::class, 'simulateWebhook'])->name('payment.simulate-webhook');

        Route::get('/payment/success', fn() => view('payments.success'))->name('payment.success');
        Route::get('/payment/cancel', fn() => view('payments.cancel'))->name('payment.cancel');
        Route::get('/payments/history', [PaymentController::class, 'history'])->name('payments.history');
        Route::get('/payments/{payment}/receipt', [PaymentController::class, 'downloadReceipt'])->name('payments.receipt');
        Route::post('/payments/upload-proof', [PaymentController::class, 'uploadProof'])->name('payments.upload-proof');
    });

    // ==================== MESSAGERIE ====================
    Route::get('/messages/fetch', [App\Http\Controllers\MessageController::class, 'getMessages'])->name('messages.fetch');
    Route::post('/messages/{message}/react', [App\Http\Controllers\MessageController::class, 'react'])->name('messages.react');
    Route::post('/messages/{message}/report', [App\Http\Controllers\MessageController::class, 'report'])->name('messages.report');
    Route::put('/messages/{message}', [App\Http\Controllers\MessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{message}', [App\Http\Controllers\MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/messages/{message}/hide', [App\Http\Controllers\MessageController::class, 'hide'])->name('messages.hide');
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');

    // Conversations
    Route::post('/conversations/{conversation}/read', [App\Http\Controllers\ConversationController::class, 'markAsRead'])->name('conversations.read');
    Route::get('/conversations/search', [App\Http\Controllers\ConversationController::class, 'searchUsers'])->name('conversations.search');
    Route::post('/conversations/{conversation}/participants', [App\Http\Controllers\ConversationController::class, 'addParticipant'])->name('conversations.participants.add');
    Route::delete('/conversations/{conversation}/participants/{user}', [App\Http\Controllers\ConversationController::class, 'removeParticipant'])->name('conversations.participants.remove');
    Route::post('/conversations/{conversation}/leave', [App\Http\Controllers\ConversationController::class, 'leaveConversation'])->name('conversations.leave');
    Route::apiResource('conversations', App\Http\Controllers\ConversationController::class);

    // ==================== CONVOCATIONS ====================
    Route::middleware('role:student|scolarite|admin')->group(function () {
        Route::get('/convocations', [ConvocationController::class, 'index'])->name('convocations.index');
        Route::get('/convocations/{convocation}/download', [ConvocationController::class, 'download'])->name('convocations.download');
    });

    // ==================== TEST MVOLA (TEMPORARY) ====================
    Route::get('/test-mvola-auth', function () {
        $clientId = config('services.mvola.client_id');
        $clientSecret = config('services.mvola.client_secret');
        $authUrl = config('services.mvola.auth_url');

        try {
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Basic ' . base64_encode("$clientId:$clientSecret"),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->asForm()->post($authUrl, [
                        'grant_type' => 'client_credentials',
                        'scope' => 'EXT_INT_MVOLA_SCOPE'
                    ]);

            return response()->json([
                'success' => $response->successful(),
                'status' => $response->status(),
                'config' => [
                    'client_id' => $clientId,
                    'auth_url' => $authUrl,
                    'merchant_msisdn' => config('services.mvola.merchant_msisdn'),
                    'base_url' => config('services.mvola.base_url'),
                    'callback_url' => config('services.mvola.callback_url'),
                ],
                'response' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    })->name('test.mvola.auth');
});

// ==================== VÉRIFICATION PUBLIQUE ====================
// Public Verification
Route::get('/verify', [VerificationController::class, 'index'])->name('verify.index');
Route::post('/verify', [VerificationController::class, 'verify'])->name('verify.process');
Route::get('/verify/payment/{code}', [VerificationController::class, 'verifyPayment'])->name('verify.payment');
Route::get('/verify/convocation/{code}', [VerificationController::class, 'verifyConvocation'])->name('verify.convocation');
Route::get('/verify/transcript/{code}', [VerificationController::class, 'verifyTranscript'])->name('verify.transcript');
Route::post('/verify/payment', [VerificationController::class, 'verifyPayment'])->name('verify.payment');

// ==================== ROUTES ADMIN ====================
Route::get('/admin/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'store']);
Route::post('/admin/logout', [App\Http\Controllers\Auth\AdminLoginController::class, 'destroy'])->name('admin.logout');

Route::middleware(['auth', 'role:admin|comptable|scolarite'])->prefix('admin')->group(function () {

    // Gestion des Utilisateurs (Admin uniquement)
    Route::middleware('role:admin')->group(function () {
        Route::get('/users/export-pdf', [App\Http\Controllers\Admin\UserController::class, 'exportPdf'])->name('admin.users.export-pdf');
        Route::post('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
        Route::post('/users/{user}/force-reset', [App\Http\Controllers\Admin\UserController::class, 'forcePasswordReset'])->name('admin.users.force-reset');

        // Configuration Système
        Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');

        Route::resource('users', App\Http\Controllers\Admin\UserController::class)->names([
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ]);

        Route::get('/staff-requests', [App\Http\Controllers\Admin\StaffRequestController::class, 'index'])->name('admin.staff-requests.index');
        Route::post('/staff-requests/{user}/approve', [App\Http\Controllers\Admin\StaffRequestController::class, 'approve'])->name('admin.staff-requests.approve');
        Route::post('/staff-requests/{user}/reject', [App\Http\Controllers\Admin\StaffRequestController::class, 'reject'])->name('admin.staff-requests.reject');

        Route::get('/audit-logs', [App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('admin.audit-logs.index');
        Route::get('/audit-logs/{auditLog}', [App\Http\Controllers\Admin\AuditLogController::class, 'show'])->name('admin.audit-logs.show');

        // Impersonation
        Route::post('/impersonate/{user}', [App\Http\Controllers\Admin\ImpersonationController::class, 'impersonate'])->name('admin.impersonate');
        Route::get('/stop-impersonating', [App\Http\Controllers\Admin\ImpersonationController::class, 'stopImpersonating'])->name('admin.stop-impersonating');

        // Badges & Trust Score
        Route::post('/users/{user}/badges', [App\Http\Controllers\Admin\UserController::class, 'assignBadge'])->name('admin.users.assign-badge');
        Route::post('/users/{user}/trust-score', [App\Http\Controllers\Admin\UserController::class, 'updateTrustScore'])->name('admin.users.update-trust-score');
    });

    // Rapprochement Bancaire (Comptable + Admin)
    Route::middleware('role:admin|comptable')->group(function () {
        Route::get('/payments', [PaymentController::class, 'allPayments'])->name('admin.payments.index');
        Route::post('/payments/{payment}/validate-proof', [PaymentController::class, 'validateProof'])->name('admin.payments.validate-proof');
        Route::post('/payments/{payment}/reject-proof', [PaymentController::class, 'rejectProof'])->name('admin.payments.reject-proof');
        Route::post('/payments/{payment}/internal-note', [PaymentController::class, 'addInternalNote'])->name('admin.payments.internal-note');

        Route::get('/reconciliation', [ReconciliationController::class, 'index'])->name('admin.reconciliation.index');
        Route::post('/reconciliation/import', [ReconciliationController::class, 'import'])->name('admin.reconciliation.import');
        Route::post('/reconciliation/match', [ReconciliationController::class, 'matchOneClick'])->name('admin.reconciliation.match');
        Route::post('/reconciliation/{statement}/request-justification', [ReconciliationController::class, 'requestJustification'])->name('admin.reconciliation.request-justification');
        Route::get('/reconciliation/report', [ReconciliationController::class, 'report'])->name('admin.reconciliation.report');
        Route::get('/reconciliation/exceptions', [ReconciliationController::class, 'exceptions'])->name('admin.reconciliation.exceptions');
        Route::post('/reconciliation/manual-match', [ReconciliationController::class, 'manualMatch'])->name('admin.reconciliation.manual-match');
    });

    // Scolarité / Examens (Scolarité + Admin)
    Route::middleware('role:admin|scolarite')->group(function () {
        Route::get('/scolarite', [App\Http\Controllers\Admin\ScolariteController::class, 'dashboard'])->name('admin.scolarite.dashboard');

        // Sessions d'examen
        Route::resource('sessions', App\Http\Controllers\Admin\ExamSessionController::class)->names('admin.sessions');

        // Signalements
        Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');
        Route::post('/reports/{report}/resolve', [App\Http\Controllers\Admin\ReportController::class, 'resolve'])->name('admin.reports.resolve');
        Route::post('/reports/{report}/dismiss', [App\Http\Controllers\Admin\ReportController::class, 'dismiss'])->name('admin.reports.dismiss');
        Route::delete('/reports/{report}/message', [App\Http\Controllers\Admin\ReportController::class, 'destroyMessage'])->name('admin.reports.destroy_message');

        // Résultats
        Route::get('/results', [App\Http\Controllers\Admin\ResultController::class, 'index'])->name('admin.results.index');
        Route::post('/results', [App\Http\Controllers\Admin\ResultController::class, 'store'])->name('admin.results.store');
        Route::patch('/results/{result}', [App\Http\Controllers\Admin\ResultController::class, 'update'])->name('admin.results.update');
        Route::post('/results/publish', [App\Http\Controllers\Admin\ResultController::class, 'publish'])->name('admin.results.publish');

        // Communications
        Route::resource('communications', App\Http\Controllers\Admin\CommunicationController::class)->names('admin.communications');
        Route::post('/communications/{communication}/send', [App\Http\Controllers\Admin\CommunicationController::class, 'send'])->name('admin.communications.send');

        // Convocations (étendu)
        Route::get('/convocations/create', [ConvocationController::class, 'create'])->name('admin.convocations.create');
        Route::post('/convocations/generate', [ConvocationController::class, 'generate'])->name('admin.convocations.generate');
        Route::post('/convocations/bulk-generate', [ConvocationController::class, 'bulkGenerate'])->name('admin.convocations.bulk-generate');
        Route::post('/convocations/send', [ConvocationController::class, 'send'])->name('admin.convocations.send');
        Route::post('/convocations/{convocation}/regenerate', [ConvocationController::class, 'regenerate'])->name('admin.convocations.regenerate');
        Route::post('/convocations/{convocation}/deactivate', [ConvocationController::class, 'deactivate'])->name('admin.convocations.deactivate');
        Route::get('/convocations/scan/{qrCode}', [ConvocationController::class, 'scan'])->name('admin.convocations.scan');

        Route::get('/students', [App\Http\Controllers\StudentController::class, 'index'])->name('admin.students.index');
        Route::get('/students/{student}', [App\Http\Controllers\StudentController::class, 'show'])->name('admin.students.show');

        // ==================== LMS V2 (Scolarité) ====================
        Route::prefix('lms')->name('admin.lms.')->group(function () {
            // Course Management
            Route::resource('courses', App\Http\Controllers\Admin\LmsCourseController::class);
            Route::post('/courses/{course}/modules', [App\Http\Controllers\Admin\LmsCourseController::class, 'addModule'])->name('courses.modules.store');
            Route::post('/modules/{module}/lessons', [App\Http\Controllers\Admin\LmsCourseController::class, 'addLesson'])->name('modules.lessons.store');
        });
    });

    // Vérification Stats
    Route::get('/verification/stats', [VerificationController::class, 'stats'])->name('admin.verification.stats');

    // Exports
    Route::get('/export/payments/csv', [App\Http\Controllers\ExportController::class, 'paymentsCSV'])->name('admin.export.payments.csv');
    Route::get('/export/payments/pdf', [App\Http\Controllers\ExportController::class, 'paymentsPDF'])->name('admin.export.payments.pdf');
    Route::get('/export/reconciliation/csv', [App\Http\Controllers\ExportController::class, 'reconciliationCSV'])->name('admin.export.reconciliation.csv');
    Route::get('/export/reconciliation/pdf', [App\Http\Controllers\ExportController::class, 'reconciliationPDF'])->name('admin.export.reconciliation.pdf');
    Route::get('/export/audit-logs/csv', [App\Http\Controllers\ExportController::class, 'auditLogsCSV'])->name('admin.export.audit-logs.csv');
    Route::get('/export/students/csv', [App\Http\Controllers\ExportController::class, 'studentsCSV'])->name('admin.export.students.csv');
    // ==================== MESSAGERIE ADMIN ====================
    Route::get('/messages', [App\Http\Controllers\Admin\MessageController::class, 'index'])->name('admin.messages.index');
    Route::get('/messages/{user}', [App\Http\Controllers\Admin\MessageController::class, 'show'])->name('admin.messages.show');
    Route::post('/messages/{user}', [App\Http\Controllers\Admin\MessageController::class, 'store'])->name('admin.messages.store');
});

// ==================== DASHBOARD STANDALONE ====================

// Staff Recovery Routes
Route::get('/staff-recovery', [\App\Http\Controllers\Auth\StaffRecoveryController::class, 'create'])
    ->name('staff.recovery');
Route::post('/staff-recovery', [\App\Http\Controllers\Auth\StaffRecoveryController::class, 'store'])
    ->name('staff.recovery.store');