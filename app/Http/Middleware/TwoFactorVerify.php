<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            // Tout le monde doit être vérifié par OTP
            if (!$request->session()->has('auth.two_factor_verified')) {
                if (
                    $request->is('two-factor-challenge*') ||
                    $request->is('logout') ||
                    $request->is('verify-email*') ||
                    $request->is('email/verification-notification') ||
                    $request->is('switch-account*') ||
                    $request->is('switch-password*')
                ) {
                    return $next($request);
                }

                return redirect()->route('two-factor.login');
            }
        }

        return $next($request);
    }
}
