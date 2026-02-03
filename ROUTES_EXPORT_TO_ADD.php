use App\Http\Controllers\ExportController;

// ... (garder tout le contenu existant)

// Ajouter ces routes dans la section ROUTES ADMIN, après les routes de convocations:

// Export (Admin + Comptable)
Route::middleware('role:admin|comptable')->prefix('export')->group(function () {
Route::get('/payments/csv', [ExportController::class, 'paymentsCSV'])->name('export.payments.csv');
Route::get('/payments/pdf', [ExportController::class, 'paymentsPDF'])->name('export.payments.pdf');
Route::get('/reconciliation/csv', [ExportController::class, 'reconciliationCSV'])->name('export.reconciliation.csv');
Route::get('/reconciliation/pdf', [ExportController::class, 'reconciliationPDF'])->name('export.reconciliation.pdf');
Route::get('/audit-logs/csv', [ExportController::class, 'auditLogsCSV'])->name('export.audit.csv');
});