@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Mes Convocations</h1>
            <p class="text-gray-600">Téléchargez vos convocations d'examens</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($convocations as $convocation)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-4">
                        <h3 class="text-white font-bold text-lg">{{ ucfirst($convocation->examSession->type) }}</h3>
                        <p class="text-blue-100 text-sm">{{ $convocation->examSession->center }}</p>
                    </div>

                    <div class="p-6 space-y-3">
                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="font-semibold">{{ $convocation->examSession->date->format('d/m/Y') }}</span>
                        </div>

                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $convocation->examSession->time }}</span>
                        </div>

                        @if($convocation->examSession->room)
                            <div class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                <span>Salle {{ $convocation->examSession->room }}</span>
                            </div>
                        @endif

                        <div class="pt-3 border-t">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $convocation->status === 'verified' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $convocation->status === 'downloaded' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $convocation->status === 'sent' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $convocation->status === 'generated' ? 'bg-gray-100 text-gray-800' : '' }}
                                ">
                                {{ ucfirst($convocation->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="px-6 pb-6">
                        <a href="{{ route('convocations.download', $convocation) }}"
                            class="block w-full bg-blue-600 text-white text-center px-4 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                            📥 Télécharger PDF
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-gray-100 rounded-lg p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <p class="text-gray-600 text-lg">Aucune convocation disponible pour le moment</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
