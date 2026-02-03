@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Historique des Paiements</h1>
            <p class="text-gray-600">Consultez tous vos paiements et téléchargez vos reçus</p>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Fournisseur</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Montant</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Statut</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                                        {{ ucfirst($payment->type ?? 'Paiement') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">{{ strtoupper($payment->provider) }}</td>
                                <td class="px-4 py-3 text-sm text-right font-bold">{{ number_format($payment->amount, 0) }} Ar
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($payment->status === 'paid')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            ✓ Payé
                                        </span>
                                    @elseif($payment->status === 'pending')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                            ⏳ En attente
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            ✗ Échoué
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($payment->status === 'paid')
                                        <a href="{{ route('payments.receipt', $payment) }}"
                                            class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm">
                                            📄 Reçu
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    Aucun paiement enregistré
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
@endsection