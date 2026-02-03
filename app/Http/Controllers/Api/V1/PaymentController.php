<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Get user's payments
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('student')) {
            $payments = $user->payments()->latest()->paginate(20);
        } else {
            // Admin/Comptable: all payments
            $payments = Payment::with('user')->latest()->paginate(50);
        }

        return response()->json($payments);
    }

    /**
     * Get single payment
     */
    public function show(Request $request, Payment $payment)
    {
        // Authorization
        if ($request->user()->hasRole('student') && $payment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        return response()->json($payment->load('user'));
    }

    /**
     * Initiate payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:scolarite,examen',
            'amount' => 'required|numeric|min:1000',
            'provider' => 'required|in:mvola,orange,airtel',
            'phone' => 'required|string',
        ]);

        $payment = Payment::create([
            'user_id' => $request->user()->id,
            'type' => $request->type,
            'amount' => $request->amount,
            'provider' => $request->provider,
            'phone' => $request->phone,
            'transaction_id' => 'TXN-' . strtoupper(uniqid()),
            'status' => 'pending',
            'method' => 'mobile_money',
        ]);

        // TODO: Initiate payment with provider API

        return response()->json([
            'message' => 'Paiement initié',
            'payment' => $payment,
        ], 201);
    }

    /**
     * Download receipt
     */
    public function downloadReceipt(Request $request, Payment $payment)
    {
        // Authorization
        if ($request->user()->hasRole('student') && $payment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        if (!$payment->receipt_url) {
            return response()->json(['message' => 'Reçu non disponible'], 404);
        }

        return response()->json([
            'receipt_url' => url('storage/' . $payment->receipt_url),
        ]);
    }
}
