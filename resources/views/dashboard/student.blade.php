@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header avec bouton switcher -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h1 class="text-3xl font-bold text-gray-800">Tableau de Bord Étudiant</h1>

            <div class="flex items-center gap-4 w-full md:w-auto">
                <form action="{{ route('dashboard') }}" method="GET" class="relative w-full md:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..."
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

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-lg">
                <h3 class="text-sm font-semibold opacity-90">Total Payé</h3>
                <p class="text-3xl font-bold mt-2">{{ number_format($data['paid_amount'], 0) }} Ar</p>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg">
                <h3 class="text-sm font-semibold opacity-90">Paiements</h3>
                <p class="text-3xl font-bold mt-2">{{ $data['total_payments'] }}</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-6 rounded-lg shadow-lg">
                <h3 class="text-sm font-semibold opacity-90">En Attente</h3>
                <p class="text-3xl font-bold mt-2">{{ $data['pending_payments'] }}</p>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-lg">
                <h3 class="text-sm font-semibold opacity-90">Convocations</h3>
                <p class="text-3xl font-bold mt-2">{{ $data['convocations_count'] }}</p>
            </div>
        </div>

        <!-- Accès Rapide aux Cours -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Accès Rapide aux Cours</h2>
                <a href="{{ route('courses') }}"
                    class="text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1">
                    Voir tous les cours
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Mathématiques -->
                <a href="{{ route('course.show', 'mathematiques') }}"
                    class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold">
                                12 leçons
                            </div>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Mathématiques</h3>
                        <p class="text-sm text-blue-100">Algèbre, Géométrie, Analyse</p>
                    </div>
                    <div class="p-4 bg-gray-50 group-hover:bg-blue-50 transition-colors">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 group-hover:text-blue-600 font-medium">Commencer →</span>
                            <div class="flex items-center gap-1 text-gray-500">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z">
                                    </path>
                                </svg>
                                <span class="text-xs">Niveau: Bac</span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Physique -->
                <a href="{{ route('course.show', 'physique') }}"
                    class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold">
                                10 leçons
                            </div>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Physique</h3>
                        <p class="text-sm text-purple-100">Mécanique, Électricité, Optique</p>
                    </div>
                    <div class="p-4 bg-gray-50 group-hover:bg-purple-50 transition-colors">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 group-hover:text-purple-600 font-medium">Commencer →</span>
                            <div class="flex items-center gap-1 text-gray-500">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z">
                                    </path>
                                </svg>
                                <span class="text-xs">Niveau: Bac</span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Chimie -->
                <a href="{{ route('course.show', 'chimie') }}"
                    class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2">
                    <div class="bg-gradient-to-br from-pink-500 to-pink-600 p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                    </path>
                                </svg>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold">
                                8 leçons
                            </div>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Chimie</h3>
                        <p class="text-sm text-pink-100">Organique, Minérale, Réactions</p>
                    </div>
                    <div class="p-4 bg-gray-50 group-hover:bg-pink-50 transition-colors">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 group-hover:text-pink-600 font-medium">Commencer →</span>
                            <div class="flex items-center gap-1 text-gray-500">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z">
                                    </path>
                                </svg>
                                <span class="text-xs">Niveau: Bac</span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Biologie -->
                <a href="{{ route('course.show', 'biologie') }}"
                    class="group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <div class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold">
                                9 leçons
                            </div>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Biologie</h3>
                        <p class="text-sm text-green-100">Cellules, Génétique, Écologie</p>
                    </div>
                    <div class="p-4 bg-gray-50 group-hover:bg-green-50 transition-colors">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 group-hover:text-green-600 font-medium">Commencer →</span>
                            <div class="flex items-center gap-1 text-gray-500">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z">
                                    </path>
                                </svg>
                                <span class="text-xs">Niveau: Bac</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Paiements récents -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Paiements Récents</h2>
                <div class="space-y-3">
                    @forelse($data['recent_payments'] as $payment)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <div>
                                <p class="font-semibold">{{ number_format($payment->amount, 0) }} Ar</p>
                                <p class="text-sm text-gray-600">{{ $payment->created_at->format('d/m/Y') }}</p>
                            </div>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold
                                                            {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $payment->status }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucun paiement trouvé</p>
                    @endforelse
                </div>
                <a href="{{ route('payments.history') }}" class="block mt-4 text-blue-600 hover:text-blue-800 text-center">
                    Voir tout →
                </a>
            </div>

            <!-- Convocations à venir -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Convocations à Venir</h2>
                <div class="space-y-3">
                    @forelse($data['upcoming_convocations'] as $convocation)
                        <div class="p-3 bg-gray-50 rounded">
                            <p class="font-semibold">{{ ucfirst($convocation->examSession->type) }}</p>
                            <p class="text-sm text-gray-600">{{ $convocation->examSession->date->format('d/m/Y') }} -
                                {{ $convocation->examSession->center }}
                            </p>
                            <a href="{{ route('convocations.download', $convocation) }}"
                                class="text-blue-600 text-sm hover:underline">
                                Télécharger →
                            </a>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucune convocation trouvée</p>
                    @endforelse
                </div>
                <a href="{{ route('convocations.index') }}"
                    class="block mt-4 text-blue-600 hover:text-blue-800 text-center">
                    Voir tout →
                </a>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('payments') }}"
                class="bg-blue-600 text-white p-6 rounded-lg hover:bg-blue-700 transition text-center">
                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <span class="font-semibold">Effectuer un Paiement</span>
            </a>
            <a href="{{ route('convocations.index') }}"
                class="bg-purple-600 text-white p-6 rounded-lg hover:bg-purple-700 transition text-center">
                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span class="font-semibold">Mes Convocations</span>
            </a>
            <a href="{{ route('payments.history') }}"
                class="bg-green-600 text-white p-6 rounded-lg hover:bg-green-700 transition text-center">
                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                <span class="font-semibold">Historique</span>
            </a>
        </div>
    </div>
@endsection