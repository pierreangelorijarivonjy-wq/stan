<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StaffRequestController extends Controller
{
    public function index()
    {
        $requests = User::where('status', 'pending')->get();
        return view('admin.staff-requests.index', compact('requests'));
    }

    public function approve(User $user)
    {
        if ($user->status !== 'pending') {
            return back()->with('error', 'Cette demande n\'est plus en attente.');
        }

        $user->update([
            'status' => 'active',
        ]);

        if ($user->requested_role) {
            $user->assignRole($user->requested_role);
        }

        // Envoyer un email de confirmation
        $user->notify(new \App\Notifications\StaffRequestApproved($user));

        return back()->with('success', "La demande de {$user->name} a été approuvée.");
    }

    public function reject(User $user)
    {
        if ($user->status !== 'pending') {
            return back()->with('error', 'Cette demande n\'est plus en attente.');
        }

        $user->update([
            'status' => 'rejected',
        ]);

        // Envoyer un email de notification
        $user->notify(new \App\Notifications\StaffRequestRejected($user));

        return back()->with('success', "La demande de {$user->name} a été rejetée.");
    }
}
