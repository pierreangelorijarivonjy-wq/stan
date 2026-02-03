<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Si pas d'utilisateur connecté, laisser passer (géré par auth middleware)
        if (!$user) {
            return $next($request);
        }

        // Si l'utilisateur n'a pas vérifié son email via OTP
        if (!$user->email_verified_via_otp) {
            // Permettre l'accès aux routes OTP et logout
            if ($request->routeIs('otp.*') || $request->routeIs('logout')) {
                return $next($request);
            }

            // Rediriger vers la page de vérification OTP
            return redirect()->route('otp.show')
                ->with('warning', 'Veuillez vérifier votre email pour accéder à votre compte.');
        }

        return $next($request);
    }
}
