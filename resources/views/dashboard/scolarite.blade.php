@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header avec bouton switcher -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h1 class="text-3xl font-bold text-gray-800">Tableau de Bord Scolarité</h1>

            <div class="flex items-center gap-4 w-full md:w-auto">
                <form action="{{ route('dashboard') }}" method="GET" class="relative w-full md:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher étudiants..."
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
                <h3 class="text-sm font-semibold opacity-90">Étudiants Actifs</h3>
                <p class="text-3xl font-bold mt-2">{{ $data['total_students'] }}</p>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg">
                <h3 class="text-sm font-semibold opacity-90">Sessions</h3>
                <p class="text-3xl font-bold mt-2">{{ $data['total_sessions'] }}</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-6 rounded-lg shadow-lg">
                <h3 class="text-sm font-semibold opacity-90">Sessions à Venir</h3>
                <p class="text-3xl font-bold mt-2">{{ $data['upcoming_sessions'] }}</p>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-lg">
                <h3 class="text-sm font-semibold opacity-90">Convocations</h3>
                <p class="text-3xl font-bold mt-2">{{ $data['convocations_generated'] }}</p>
                <p class="text-sm opacity-90 mt-1">{{ $data['convocations_sent'] }} envoyées</p>
            </div>
        </div>

        @if(isset($data['search_results']) && count($data['search_results']) > 0)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Résultats de recherche</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Matricule</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($data['search_results'] as $student)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $student->matricule }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $student->first_name }} {{ $student->last_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $student->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $student->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Sessions récentes -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Sessions Récentes</h2>
                <div class="space-y-3">
                    @forelse($data['recent_sessions'] as $session)
                        <div class="p-3 bg-gray-50 rounded">
                            <p class="font-semibold">{{ ucfirst($session->type) }}</p>
                            <p class="text-sm text-gray-600">{{ $session->center }} - {{ $session->date->format('d/m/Y') }}</p>
                            <span
                                class="text-xs px-2 py-1 rounded-full
                                                    {{ $session->status === 'planned' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $session->status === 'ongoing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $session->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}">
                                {{ ucfirst($session->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucune session trouvée</p>
                    @endforelse
                </div>
            </div>

            <!-- Étudiants par statut -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Étudiants par Statut</h2>
                <div class="space-y-3">
                    @forelse($data['students_by_status'] as $item)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <span class="font-semibold">{{ ucfirst($item->status) }}</span>
                            <span class="text-2xl font-bold text-blue-600">{{ $item->count }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucune donnée</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('admin.convocations.create') }}"
                class="bg-blue-600 text-white p-6 rounded-lg hover:bg-blue-700 transition text-center">
                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span class="font-semibold">Générer des Convocations</span>
            </a>
            <a href="{{ route('verify.scan') }}"
                class="bg-purple-600 text-white p-6 rounded-lg hover:bg-purple-700 transition text-center">
                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                    </path>
                </svg>
                <span class="font-semibold">Scanner QR Codes</span>
            </a>
        </div>
    </div>
@endsection