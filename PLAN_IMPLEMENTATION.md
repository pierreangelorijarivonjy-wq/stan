# 🚀 PLAN D'IMPLÉMENTATION - Fonctionnalités Manquantes
## EduPass-MG V1 - Complétion MVP

---

## 📅 SPRINT 1: Communications (3-4 jours)

### Tâche 1.1: Envoi Email ⏱️ 1.5 jours

#### Fichiers à créer:
```
app/Mail/ConvocationMail.php
app/Mail/PaymentReceiptMail.php
resources/views/emails/convocation.blade.php
resources/views/emails/payment-receipt.blade.php
```

#### Étapes:
1. **Créer Mailable pour convocations**
   ```bash
   php artisan make:mail ConvocationMail
   ```

2. **Template email convocation**
   - Design responsive
   - Lien de téléchargement PDF
   - Informations session
   - Bouton CTA "Télécharger"

3. **Template email reçu paiement**
   - Confirmation paiement
   - Montant et référence
   - Lien vers reçu PDF

4. **Configuration SMTP**
   - Tester avec Mailtrap (dev)
   - Configurer SMTP production
   - Ajouter dans `.env`:
     ```
     MAIL_MAILER=smtp
     MAIL_HOST=smtp.gmail.com
     MAIL_PORT=587
     MAIL_USERNAME=edupass@example.com
     MAIL_PASSWORD=your_password
     MAIL_ENCRYPTION=tls
     ```

5. **Modifier ConvocationController**
   ```php
   // Ligne 168 - Remplacer TODO par:
   Mail::to($student->email)->send(new ConvocationMail($convocation));
   ```

6. **Ajouter queue pour emails**
   ```php
   Mail::to($student->email)->queue(new ConvocationMail($convocation));
   ```

#### Tests:
- [ ] Email reçu dans boîte de réception
- [ ] Lien de téléchargement fonctionne
- [ ] Design responsive sur mobile
- [ ] Pas de spam

---

### Tâche 1.2: Envoi SMS ⏱️ 1 jour

#### Fichiers à créer:
```
app/Services/SmsService.php
config/sms.php
```

#### Étapes:
1. **Choisir fournisseur SMS**
   - Option 1: Nexah (Madagascar)
   - Option 2: Twilio (international)
   - Option 3: SMS API locale

2. **Créer SmsService**
   ```bash
   # Créer manuellement app/Services/SmsService.php
   ```

3. **Configuration**
   ```php
   // config/sms.php
   return [
       'provider' => env('SMS_PROVIDER', 'nexah'),
       'api_key' => env('SMS_API_KEY'),
       'sender_id' => env('SMS_SENDER_ID', 'EduPass'),
       'api_url' => env('SMS_API_URL'),
   ];
   ```

4. **Implémenter envoi**
   ```php
   // SmsService.php
   public function send($phone, $message) {
       // Appel API fournisseur
       // Gestion d'erreurs
       // Logging
   }
   ```

5. **Modifier ConvocationController**
   ```php
   // Ligne 174 - Remplacer TODO par:
   app(SmsService::class)->send(
       $student->phone,
       "Convocation disponible pour {$session->type} le {$session->date->format('d/m/Y')}. Téléchargez sur " . route('convocations.index')
   );
   ```

6. **Ajouter queue pour SMS**
   ```php
   dispatch(new SendSmsJob($student->phone, $message));
   ```

#### Tests:
- [ ] SMS reçu sur téléphone test
- [ ] Message clair et concis
- [ ] Lien raccourci fonctionne
- [ ] Pas de doublons

---

### Tâche 1.3: Notifications in-app ⏱️ 1.5 jours

#### Fichiers à créer:
```
app/Notifications/ConvocationReady.php
app/Notifications/PaymentConfirmed.php
database/migrations/xxxx_create_notifications_table.php
resources/views/components/notification-bell.blade.php
```

#### Étapes:
1. **Créer table notifications**
   ```bash
   php artisan notifications:table
   php artisan migrate
   ```

2. **Créer notifications**
   ```bash
   php artisan make:notification ConvocationReady
   php artisan make:notification PaymentConfirmed
   ```

3. **Implémenter notifications**
   ```php
   // ConvocationReady.php
   public function via($notifiable) {
       return ['database'];
   }
   
   public function toArray($notifiable) {
       return [
           'title' => 'Convocation disponible',
           'message' => 'Votre convocation pour ' . $this->convocation->examSession->type,
           'action_url' => route('convocations.download', $this->convocation),
       ];
   }
   ```

4. **Composant UI cloche de notifications**
   - Badge avec nombre non lues
   - Dropdown avec liste
   - Marquer comme lu

5. **Modifier ConvocationController**
   ```php
   // Ligne 180 - Remplacer TODO par:
   $student->user->notify(new ConvocationReady($convocation));
   ```

#### Tests:
- [ ] Notification apparaît dans UI
- [ ] Badge compte correct
- [ ] Marquer comme lu fonctionne
- [ ] Lien vers action fonctionne

---

## 📅 SPRINT 2: Sécurité & Paiements (3-4 jours)

### Tâche 2.1: PaymentService ⏱️ 1 jour

#### Fichiers à créer:
```
app/Services/PaymentService.php
app/Services/MobileMoneyProviders/MVolaProvider.php
app/Services/MobileMoneyProviders/OrangeProvider.php
app/Services/MobileMoneyProviders/AirtelProvider.php
```

#### Étapes:
1. **Créer PaymentService**
   - Extraire logique de PaymentController
   - Méthodes: `initiate()`, `verify()`, `refund()`
   - Gestion d'erreurs centralisée

2. **Créer providers**
   - Interface commune `MobileMoneyProvider`
   - Implémentations spécifiques
   - Factory pattern pour sélection

3. **Refactoriser PaymentController**
   ```php
   public function initiate(Request $request) {
       $payment = $this->paymentService->initiate(
           auth()->user(),
           $request->provider,
           $request->amount,
           $request->phone
       );
       return redirect()->route('payment.success');
   }
   ```

4. **Ajouter retry automatique**
   ```php
   use Illuminate\Support\Facades\Retry;
   
   Retry::times(3)
       ->sleep(1000)
       ->whenException(function ($e) {
           return $e instanceof ConnectionException;
       })
       ->run(function () {
           // Appel API
       });
   ```

#### Tests:
- [ ] Tests unitaires PaymentService
- [ ] Mock des providers
- [ ] Gestion d'erreurs
- [ ] Retry fonctionne

---

### Tâche 2.2: Webhooks sécurisés ⏱️ 1 jour

#### Fichiers à modifier:
```
routes/api.php (créer si n'existe pas)
app/Http/Controllers/WebhookController.php
app/Http/Middleware/ValidateWebhookSignature.php
```

#### Étapes:
1. **Créer WebhookController dédié**
   ```bash
   php artisan make:controller WebhookController
   ```

2. **Validation signature**
   ```php
   // ValidateWebhookSignature middleware
   public function handle($request, Closure $next) {
       $signature = $request->header('X-Signature');
       $payload = $request->getContent();
       
       $expected = hash_hmac('sha256', $payload, config('services.webhook_secret'));
       
       if (!hash_equals($expected, $signature)) {
           abort(403, 'Invalid signature');
       }
       
       return $next($request);
   }
   ```

3. **Protection rejeu**
   ```php
   // Vérifier timestamp
   $timestamp = $request->header('X-Timestamp');
   if (abs(time() - $timestamp) > 300) { // 5 minutes
       abort(403, 'Request too old');
   }
   
   // Vérifier unicité
   $requestId = $request->header('X-Request-ID');
   if (Cache::has("webhook:{$requestId}")) {
       abort(409, 'Duplicate request');
   }
   Cache::put("webhook:{$requestId}", true, 600);
   ```

4. **Logging détaillé**
   ```php
   Log::channel('webhooks')->info('Webhook received', [
       'provider' => $provider,
       'transaction_id' => $transactionId,
       'status' => $status,
       'payload' => $request->all(),
   ]);
   ```

5. **Routes API**
   ```php
   // routes/api.php
   Route::post('/webhooks/mvola', [WebhookController::class, 'mvola'])
       ->middleware('validate.webhook.signature');
   Route::post('/webhooks/orange', [WebhookController::class, 'orange'])
       ->middleware('validate.webhook.signature');
   ```

#### Tests:
- [ ] Signature invalide rejetée
- [ ] Rejeu détecté
- [ ] Timestamp expiré rejeté
- [ ] Webhook valide traité

---

### Tâche 2.3: Chiffrement données sensibles ⏱️ 0.5 jour

#### Fichiers à modifier:
```
app/Models/Student.php
database/migrations/xxxx_add_encryption_to_students.php
```

#### Étapes:
1. **Ajouter casts encrypted**
   ```php
   // Student.php
   protected $casts = [
       'piece_id' => 'encrypted',
       'phone' => 'encrypted',
   ];
   ```

2. **Migration pour données existantes**
   ```php
   // Chiffrer données existantes
   Student::chunk(100, function ($students) {
       foreach ($students as $student) {
           $student->save(); // Re-save pour chiffrer
       }
   });
   ```

3. **Ajouter dans Payment aussi**
   ```php
   // Payment.php
   protected $casts = [
       'phone' => 'encrypted',
   ];
   ```

#### Tests:
- [ ] Données chiffrées en base
- [ ] Lecture déchiffre correctement
- [ ] Recherche fonctionne encore

---

### Tâche 2.4: Rate limiting & CAPTCHA ⏱️ 0.5 jour

#### Fichiers à modifier:
```
app/Http/Kernel.php
routes/web.php
```

#### Étapes:
1. **Rate limiting sur /verify**
   ```php
   // routes/web.php
   Route::post('/verify', [VerificationController::class, 'verify'])
       ->middleware('throttle:10,1'); // 10 requêtes par minute
   ```

2. **Rate limiting sur login**
   ```php
   Route::post('/login', [AuthController::class, 'login'])
       ->middleware('throttle:5,1');
   ```

3. **CAPTCHA sur vérification publique** (optionnel)
   ```bash
   composer require anhskohbo/no-captcha
   ```

#### Tests:
- [ ] Rate limit fonctionne
- [ ] Message d'erreur clair
- [ ] Déblocage après délai

---

## 📅 SPRINT 3: Audit & Monitoring (2-3 jours)

### Tâche 3.1: Audit trail ⏱️ 1.5 jours

#### Fichiers à créer:
```
database/migrations/xxxx_create_audit_logs_table.php
app/Models/AuditLog.php
app/Traits/Auditable.php
app/Http/Middleware/AuditMiddleware.php
```

#### Étapes:
1. **Créer table audit_logs**
   ```php
   Schema::create('audit_logs', function (Blueprint $table) {
       $table->id();
       $table->foreignId('user_id')->nullable();
       $table->string('action'); // created, updated, deleted, viewed
       $table->string('auditable_type'); // Payment, Convocation, etc.
       $table->unsignedBigInteger('auditable_id');
       $table->json('old_values')->nullable();
       $table->json('new_values')->nullable();
       $table->string('ip_address');
       $table->string('user_agent');
       $table->timestamps();
       
       $table->index(['auditable_type', 'auditable_id']);
       $table->index('created_at');
   });
   ```

2. **Créer trait Auditable**
   ```php
   trait Auditable {
       protected static function bootAuditable() {
           static::created(function ($model) {
               $model->logAudit('created');
           });
           
           static::updated(function ($model) {
               $model->logAudit('updated');
           });
           
           static::deleted(function ($model) {
               $model->logAudit('deleted');
           });
       }
       
       protected function logAudit($action) {
           AuditLog::create([
               'user_id' => auth()->id(),
               'action' => $action,
               'auditable_type' => get_class($this),
               'auditable_id' => $this->id,
               'old_values' => $this->getOriginal(),
               'new_values' => $this->getAttributes(),
               'ip_address' => request()->ip(),
               'user_agent' => request()->userAgent(),
           ]);
       }
   }
   ```

3. **Ajouter trait aux modèles sensibles**
   ```php
   class Payment extends Model {
       use Auditable;
   }
   
   class Convocation extends Model {
       use Auditable;
   }
   ```

4. **Interface de consultation**
   - Route admin pour voir logs
   - Filtres par utilisateur, action, date
   - Export CSV

#### Tests:
- [ ] Actions loggées correctement
- [ ] Anciennes/nouvelles valeurs capturées
- [ ] Interface de consultation fonctionne

---

### Tâche 3.2: Monitoring (Sentry) ⏱️ 0.5 jour

#### Étapes:
1. **Installer Sentry**
   ```bash
   composer require sentry/sentry-laravel
   php artisan sentry:publish --dsn=your_dsn
   ```

2. **Configuration**
   ```php
   // .env
   SENTRY_LARAVEL_DSN=https://xxx@sentry.io/xxx
   SENTRY_TRACES_SAMPLE_RATE=0.2
   ```

3. **Ajouter contexte utilisateur**
   ```php
   // app/Providers/AppServiceProvider.php
   \Sentry\configureScope(function (\Sentry\State\Scope $scope): void {
       if (auth()->check()) {
           $scope->setUser([
               'id' => auth()->id(),
               'email' => auth()->user()->email,
           ]);
       }
   });
   ```

#### Tests:
- [ ] Erreurs remontées dans Sentry
- [ ] Contexte utilisateur présent
- [ ] Alertes configurées

---

### Tâche 3.3: Logs structurés ⏱️ 0.5 jour

#### Fichiers à modifier:
```
config/logging.php
```

#### Étapes:
1. **Créer channel dédié pour paiements**
   ```php
   // config/logging.php
   'channels' => [
       'payments' => [
           'driver' => 'daily',
           'path' => storage_path('logs/payments.log'),
           'level' => 'info',
           'days' => 90,
       ],
       'webhooks' => [
           'driver' => 'daily',
           'path' => storage_path('logs/webhooks.log'),
           'level' => 'info',
           'days' => 90,
       ],
   ];
   ```

2. **Utiliser dans code**
   ```php
   Log::channel('payments')->info('Payment initiated', [
       'user_id' => $user->id,
       'amount' => $amount,
       'provider' => $provider,
       'transaction_id' => $transactionId,
   ]);
   ```

3. **Ajouter correlation ID**
   ```php
   // Middleware
   $correlationId = Str::uuid();
   request()->merge(['correlation_id' => $correlationId]);
   
   Log::withContext(['correlation_id' => $correlationId]);
   ```

---

## 📅 SPRINT 4: Performance & Export (2 jours)

### Tâche 4.1: Queue jobs ⏱️ 1 jour

#### Fichiers à créer:
```
app/Jobs/GenerateConvocationPdfJob.php
app/Jobs/SendConvocationEmailJob.php
app/Jobs/SendSmsJob.php
```

#### Étapes:
1. **Configurer queue**
   ```php
   // .env
   QUEUE_CONNECTION=database
   ```

2. **Créer jobs**
   ```bash
   php artisan make:job GenerateConvocationPdfJob
   php artisan make:job SendConvocationEmailJob
   php artisan make:job SendSmsJob
   ```

3. **Implémenter jobs**
   ```php
   class GenerateConvocationPdfJob implements ShouldQueue {
       use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
       
       public function handle() {
           // Générer PDF
       }
   }
   ```

4. **Dispatcher jobs**
   ```php
   // Au lieu de:
   $this->generatePDF($convocation);
   
   // Faire:
   GenerateConvocationPdfJob::dispatch($convocation);
   ```

5. **Lancer worker**
   ```bash
   php artisan queue:work --tries=3 --timeout=60
   ```

#### Tests:
- [ ] Jobs exécutés
- [ ] Retry en cas d'échec
- [ ] Logs des jobs

---

### Tâche 4.2: Cache Redis ⏱️ 0.5 jour

#### Étapes:
1. **Installer Redis** (si pas déjà fait)
   ```bash
   composer require predis/predis
   ```

2. **Configuration**
   ```php
   // .env
   CACHE_DRIVER=redis
   SESSION_DRIVER=redis
   QUEUE_CONNECTION=redis
   ```

3. **Cacher requêtes fréquentes**
   ```php
   $sessions = Cache::remember('exam_sessions', 3600, function () {
       return ExamSession::where('status', 'planned')->get();
   });
   ```

4. **Invalider cache**
   ```php
   // Quand session créée/modifiée
   Cache::forget('exam_sessions');
   ```

---

### Tâche 4.3: Export rapports ⏱️ 0.5 jour

#### Fichiers à créer:
```
app/Exports/ReconciliationReportExport.php
```

#### Étapes:
1. **Créer export**
   ```bash
   php artisan make:export ReconciliationReportExport
   ```

2. **Implémenter export**
   ```php
   class ReconciliationReportExport implements FromCollection {
       public function collection() {
           return ReconciliationMatch::with(['payment', 'bankStatement'])
               ->latest()
               ->get();
       }
       
       public function headings(): array {
           return ['Date', 'Transaction ID', 'Montant', 'Statut', 'Score'];
       }
   }
   ```

3. **Route de téléchargement**
   ```php
   Route::get('/admin/reconciliation/export', function () {
       return Excel::download(new ReconciliationReportExport, 'rapport.xlsx');
   });
   ```

---

## 📅 SPRINT 5: Documentation & Tests (2-3 jours)

### Tâche 5.1: Documentation ⏱️ 1 jour

#### Fichiers à créer/modifier:
```
README.md
docs/INSTALLATION.md
docs/DEPLOYMENT.md
docs/API.md
docs/WEBHOOKS.md
```

#### Contenu:
1. **README.md**
   - Description projet
   - Prérequis
   - Installation rapide
   - Comptes de test
   - Liens vers docs

2. **INSTALLATION.md**
   - Installation détaillée
   - Configuration .env
   - Migration base
   - Seeders
   - Tests

3. **DEPLOYMENT.md**
   - Serveur requis
   - Déploiement production
   - Configuration Nginx
   - SSL/TLS
   - Monitoring

4. **API.md**
   - Endpoints disponibles
   - Authentification
   - Exemples requêtes
   - Codes d'erreur

5. **WEBHOOKS.md**
   - Format webhooks
   - Signature validation
   - Retry policy
   - Exemples payloads

---

### Tâche 5.2: Tests automatisés ⏱️ 1.5 jours

#### Fichiers à créer:
```
tests/Feature/PaymentTest.php
tests/Feature/ConvocationTest.php
tests/Feature/ReconciliationTest.php
tests/Unit/PaymentServiceTest.php
```

#### Tests à écrire:

**PaymentTest.php**
```php
public function test_payment_initiation() {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->post('/payment/initiate', [
        'provider' => 'mvola',
        'phone' => '0340000000',
        'amount' => 50000,
    ]);
    
    $response->assertStatus(302);
    $this->assertDatabaseHas('payments', [
        'user_id' => $user->id,
        'amount' => 50000,
        'status' => 'pending',
    ]);
}

public function test_webhook_updates_payment() {
    $payment = Payment::factory()->create(['status' => 'pending']);
    
    $response = $this->post('/api/webhooks/mvola', [
        'transaction_id' => $payment->transaction_id,
        'status' => 'SUCCESS',
    ]);
    
    $response->assertStatus(200);
    $this->assertDatabaseHas('payments', [
        'id' => $payment->id,
        'status' => 'paid',
    ]);
}
```

**ConvocationTest.php**
```php
public function test_convocation_generation() {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    
    $session = ExamSession::factory()->create();
    $student = Student::factory()->create();
    
    $response = $this->actingAs($admin)->post('/admin/convocations/generate', [
        'exam_session_id' => $session->id,
        'student_ids' => [$student->id],
    ]);
    
    $response->assertStatus(302);
    $this->assertDatabaseHas('convocations', [
        'student_id' => $student->id,
        'exam_session_id' => $session->id,
    ]);
}
```

**ReconciliationTest.php**
```php
public function test_automatic_matching() {
    $payment = Payment::factory()->create([
        'amount' => 50000,
        'transaction_id' => 'EDUPASS-123',
        'status' => 'pending',
    ]);
    
    $statement = BankStatement::factory()->create([
        'amount' => 50000,
        'reference' => 'EDUPASS-123',
        'status' => 'pending',
    ]);
    
    $response = $this->actingAs($this->comptable)
        ->post('/admin/reconciliation/match');
    
    $response->assertStatus(302);
    $this->assertDatabaseHas('reconciliation_matches', [
        'payment_id' => $payment->id,
        'bank_statement_id' => $statement->id,
    ]);
}
```

---

## 📊 RÉCAPITULATIF PLANNING

| Sprint | Durée | Tâches | Priorité |
|--------|-------|--------|----------|
| 1: Communications | 3-4 jours | Email, SMS, Notifications | 🔴 CRITIQUE |
| 2: Sécurité & Paiements | 3-4 jours | PaymentService, Webhooks, Chiffrement | 🔴 CRITIQUE |
| 3: Audit & Monitoring | 2-3 jours | Audit trail, Sentry, Logs | 🟡 HAUTE |
| 4: Performance & Export | 2 jours | Queue, Cache, Export | 🟡 HAUTE |
| 5: Documentation & Tests | 2-3 jours | Docs, Tests auto | 🟢 MOYENNE |

**Total estimé**: 12-16 jours (2-3 semaines)

---

## ✅ CHECKLIST DE VALIDATION

Avant de considérer V1 comme complète:

### Fonctionnel
- [ ] Email convocations envoyés et reçus
- [ ] SMS convocations envoyés et reçus
- [ ] Notifications in-app fonctionnent
- [ ] Webhooks MVola testés en sandbox
- [ ] Webhooks Orange testés en sandbox
- [ ] Rapprochement 1 clic fonctionne
- [ ] Export rapports CSV/PDF

### Sécurité
- [ ] Données sensibles chiffrées
- [ ] Rate limiting actif
- [ ] Webhooks sécurisés (signature)
- [ ] Audit trail complet
- [ ] Pas de failles OWASP Top 10

### Performance
- [ ] Jobs en queue
- [ ] Cache Redis actif
- [ ] Pas de requêtes N+1
- [ ] PDF générés en < 10s

### Documentation
- [ ] README complet
- [ ] Guide installation
- [ ] Guide déploiement
- [ ] Documentation API

### Tests
- [ ] Tests unitaires passent
- [ ] Tests d'intégration passent
- [ ] Coverage > 70%

---

## 🎯 PROCHAINE ÉTAPE

**Commencer par Sprint 1 - Communications** car c'est le plus critique et bloquant pour le pilote.

Voulez-vous que je commence l'implémentation ?
