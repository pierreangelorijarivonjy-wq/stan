<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'badges'])->latest();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->role) {
            $query->role($request->role);
        }

        $users = $query->paginate(20)->withQueryString();
        $badges = \App\Models\Badge::all();
        return view('admin.users.index', compact('users', 'badges'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,comptable,scolarite,controleur,student',
        ]);

        if (User::isRoleLimitReached($request->role)) {
            return back()->withErrors(['role' => "La limite de 3 comptes pour le rôle {$request->role} est atteinte."]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'status' => 'active',
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:admin,comptable,scolarite,controleur,student',
            'status' => 'required|in:active,pending,rejected',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function toggleStatus(User $user)
    {
        $user->status = $user->status === 'active' ? 'pending' : 'active';
        $user->save();

        return back()->with('success', 'Statut de l\'utilisateur mis à jour.');
    }

    public function forcePasswordReset(User $user)
    {
        $newPassword = \Illuminate\Support\Str::random(10);
        $user->password = \Illuminate\Support\Facades\Hash::make($newPassword);
        $user->save();

        // Ici, on pourrait envoyer un email avec le nouveau mot de passe
        return back()->with('success', "Mot de passe réinitialisé. Nouveau mot de passe : {$newPassword}");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé définitivement.');
    }

    public function exportPdf()
    {
        $users = User::with('roles')->get();
        $pdf = Pdf::loadView('admin.users.pdf', compact('users'));
        return $pdf->download('liste-utilisateurs-edupass.pdf');
    }

    public function assignBadge(Request $request, User $user)
    {
        $request->validate([
            'badge_id' => 'required|exists:badges,id',
        ]);

        $user->badges()->syncWithoutDetaching([$request->badge_id]);

        return back()->with('success', 'Badge assigné avec succès.');
    }

    public function updateTrustScore(Request $request, User $user)
    {
        $request->validate([
            'trust_score' => 'required|integer|min:0|max:100',
        ]);

        $user->update(['trust_score' => $request->trust_score]);

        return back()->with('success', 'Score de confiance mis à jour.');
    }
}
