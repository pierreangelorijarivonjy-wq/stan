@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header avec bouton switcher -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h1 class="text-3xl font-bold text-gray-800">Tableau de Bord Comptabilité</h1>

            <div class="flex items-center gap-4 w-full md:w-auto">
                <form action="{{ route('dashboard') }}" method="GET" class="relative w-full md:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher paiements..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition shadow-sm">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>

                <a href="{{ route('account.switcher') }}"
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-4 py-2 rounded-lg font-semibold shadow-lg transition transform hover:scale-105 flex items-center gap-2 text-sm whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    Changer
                </a>
            </div>
        </div>

        <!-- Statistiques financières -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg">
                <h3 class="text-sm font-semibold opacity-90">Revenu Total</h3>
                <p class="text-3xl font-bold mt-2">{{ number_format($data['total_revenue'], 0) }} Ar</p>
            </div>
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-lg">
                <h3 class="text-sm font-semibold opacity-90">Paiements</h3>
                <p class="text-3xl font-bold mt-2">{{ $data['total_payments'] }}</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-6 rounded-lg shadow-lg">
                <h3 class="text-sm font-semibold opacity-90">En Attente</h3>
                <p class="text-3xl font-bold mt-2">{{ $data['pending_payments'] }}</p>
                <p class="text-sm opacity-90 mt-1">{{ number_format($data['pending_amount'], 0) }} Ar</p>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-lg">
                <h3 class="text-sm font-semibold opacity-90">Taux Appariement</h3>
                <p class="text-3xl font-bold mt-2">{{ number_format($data['match_rate'], 1) }}%</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Revenus par type -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Revenus par Type</h2>
                <div class="space-y-3">
                    @forelse($data['revenue_by_type'] as $item)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <span class="font-semibold">{{ ucfirst($item->type ?? 'Autre') }}</span>
                            <span class="text-green-600 font-bold">{{ number_format($item->total, 0) }} Ar</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucune donnée</p>
                    @endforelse
                </div>
            </div>

            <!-- Paiements récents -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Paiements Récents</h2>
                <div class="space-y-2">
                    @forelse($data['recent_payments'] as $payment)
                        <div class="flex justify-between items-center p-2 hover:bg-gray-50 rounded">
                            <div>
                                <p class="font-semibold text-sm">{{ $payment->user->name }}</p>
                                <p class="text-xs text-gray-600">{{ $payment->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold">{{ number_format($payment->amount, 0) }} Ar</p>
                                <span
                                    class="text-xs px-2 py-1 rounded-full
                                                        {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $payment->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucun paiement trouvé</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.reconciliation.index') }}"
                class="bg-blue-600 text-white p-6 rounded-lg hover:bg-blue-700 transition text-center">
                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                </svg>
                <span class="font-semibold">Rapprochement Bancaire</span>
            </a>
            <a href="{{ route('admin.reconciliation.report') }}"
                class="bg-purple-600 text-white p-6 rounded-lg hover:bg-purple-700 transition text-center">
                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                <span class="font-semibold">Rapports</span>
            </a>
            <a href="{{ route('admin.reconciliation.exceptions') }}"
                class="bg-red-600 text-white p-6 rounded-lg hover:bg-red-700 transition text-center">
                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <span class="font-semibold">Exceptions ({{ $data['unmatched_statements'] }})</span>
            </a>
        </div>
    </div>
@endsection