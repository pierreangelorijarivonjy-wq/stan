<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyWebhookSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $provider): Response
    {
        // Récupérer la signature du header
        $signature = $request->header('X-Signature') ?? $request->header('X-Hub-Signature');

        if (!$signature) {
            Log::warning('Webhook sans signature', [
                'provider' => $provider,
                'ip' => $request->ip()
            ]);
            return response()->json(['error' => 'Signature manquante'], 401);
        }

        // Récupérer le payload brut
        $payload = $request->getContent();

        // Récupérer le secret selon le fournisseur
        $secret = config("services.{$provider}.webhook_secret") ?? config('app.webhook_secret');

        if (!$secret) {
            Log::error('Secret webhook non configuré', ['provider' => $provider]);
            return response()->json(['error' => 'Configuration invalide'], 500);
        }

        // Calculer la signature attendue
        $expectedSignature = hash_hmac('sha256', $payload, $secret);

        // Comparer les signatures de manière sécurisée
        if (!hash_equals($expectedSignature, $signature)) {
            Log::error('Signature webhook invalide', [
                'provider' => $provider,
                'ip' => $request->ip(),
                'expected' => $expectedSignature,
                'received' => $signature,
            ]);
            return response()->json(['error' => 'Signature invalide'], 403);
        }

        // Protection contre les attaques par rejeu (replay attack)
        $timestamp = $request->header('X-Timestamp');
        if ($timestamp) {
            $timeDiff = time() - (int) $timestamp;

            // Rejeter si plus de 5 minutes
            if ($timeDiff > 300 || $timeDiff < -300) {
                Log::warning('Webhook expiré ou futur', [
                    'provider' => $provider,
                    'time_diff' => $timeDiff
                ]);
                return response()->json(['error' => 'Requête expirée'], 403);
            }
        }

        // Logger le succès
        Log::info('Webhook vérifié avec succès', [
            'provider' => $provider,
            'ip' => $request->ip()
        ]);

        return $next($request);
    }
}
