<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function impersonate(User $user)
    {
        if ($user->hasRole('admin')) {
            return back()->with('error', 'Vous ne pouvez pas usurper un administrateur.');
        }

        // On stocke l'ID de l'admin original dans la session
        session()->put('impersonate', Auth::id());

        // On connecte l'utilisateur cible
        Auth::login($user);

        return redirect('/dashboard')->with('success', "Vous êtes maintenant connecté en tant que {$user->name}");
    }

    public function stopImpersonating()
    {
        if (!session()->has('impersonate')) {
            return redirect('/dashboard');
        }

        Auth::loginUsingId(session('impersonate'));
        session()->forget('impersonate');

        return redirect()->route('admin.users.index')->with('success', 'Retour au compte administrateur.');
    }
}
