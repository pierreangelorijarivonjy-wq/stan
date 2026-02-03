<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Communication;
use Illuminate\Http\Request;

class CommunicationController extends Controller
{
    public function index()
    {
        $communications = Communication::latest()->paginate(20);
        return view('admin.communications.index', compact('communications'));
    }

    public function create()
    {
        return view('admin.communications.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'type' => 'required|in:convocation,calendar,announcement',
            'channels' => 'required|array',
            'target' => 'required|string',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $communication = Communication::create($validated);

        if (!$request->scheduled_at) {
            $this->send($communication);
        }

        return redirect()->route('admin.communications.index')->with('success', 'Communication enregistrée.');
    }

    public function send(Communication $communication)
    {
        // Logique d'envoi via les différents canaux
        // Ceci pourrait être un Job

        $communication->update([
            'sent_at' => now(),
            'status' => 'sent'
        ]);

        return back()->with('success', 'Communication envoyée.');
    }
}
