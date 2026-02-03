@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header avec bouton switcher et retour -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
            <div>
                <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400" id="admin-title">
                    Tableau de Bord Administrateur
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium" id="admin-subtitle">Vue d'ensemble et gestion du système</p>
            </div>
            
            <div class="flex flex-col md:flex-row gap-4 items-center">
                <form action="{{ route('dashboard') }}" method="GET" class="relative w-full md:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher..." 
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition shadow-sm">
                    <div class="absolute left-3 top-3.5 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>

                <div class="flex gap-4">
                    <a href="{{ route('dashboard') }}"
                        class="bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 px-6 py-3 rounded-xl font-bold shadow-sm hover:shadow-md hover:bg-slate-50 dark:hover:bg-slate-700 transition flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span id="btn-back-global">Dashboard Global</span>
                    </a>
                    
                    <a href="{{ route('account.switcher') }}"
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition transform flex items-center gap-2">
                        <i class="fas fa-exchange-alt"></i>
                        <span id="btn-change-role">Changer de Rôle</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistiques globales -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <!-- Users -->
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20 hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider" id="stat-users-label">Utilisateurs</h3>
                        <p class="text-3xl font-black text-slate-800 dark:text-white mt-1">{{ $data['total_users'] }}</p>
                    </div>
                </div>
                <p class="text-sm text-slate-500 font-medium bg-slate-100 dark:bg-slate-700/50 py-1 px-3 rounded-full inline-block">
                    <span class="font-bold text-blue-600">{{ $data['total_students'] }}</span> <span id="stat-students-label">étudiants</span>
                </p>
            </div>

            <!-- Revenue -->
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20 hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider" id="stat-revenue-label">Revenu Total</h3>
                        <p class="text-3xl font-black text-slate-800 dark:text-white mt-1">{{ number_format($data['total_revenue'] / 1000000, 1) }}M <span class="text-lg font-normal text-slate-400">Ar</span></p>
                    </div>
                </div>
                <p class="text-sm text-slate-500 font-medium bg-slate-100 dark:bg-slate-700/50 py-1 px-3 rounded-full inline-block">
                    <span class="font-bold text-green-600">{{ $data['total_payments'] }}</span> <span id="stat-payments-label">paiements</span>
                </p>
            </div>

            <!-- Pending -->
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20 hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider" id="stat-pending-label">En Attente</h3>
                        <p class="text-3xl font-black text-slate-800 dark:text-white mt-1">{{ $data['pending_payments'] }}</p>
                    </div>
                </div>
                <p class="text-sm text-slate-500 font-medium bg-slate-100 dark:bg-slate-700/50 py-1 px-3 rounded-full inline-block">
                    <span id="stat-pending-sub">paiements à valider</span>
                </p>
            </div>

            <!-- Convocations -->
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20 hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 text-purple-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider" id="stat-convocations-label">Convocations</h3>
                        <p class="text-3xl font-black text-slate-800 dark:text-white mt-1">{{ $data['total_convocations'] }}</p>
                    </div>
                </div>
                <p class="text-sm text-slate-500 font-medium bg-slate-100 dark:bg-slate-700/50 py-1 px-3 rounded-full inline-block">
                    <span class="font-bold text-purple-600">{{ $data['total_sessions'] }}</span> <span id="stat-sessions-label">sessions</span>
                </p>
            </div>
        </div>

        <!-- Notifications Section -->
        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-8 mb-10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-3">
                    <i class="fas fa-bell text-indigo-500"></i> <span id="notifications-title">Notifications d'Activité</span>
                </h2>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                            <i class="fas fa-check-double"></i> <span id="mark-all-read">Tout marquer comme lu</span>
                        </button>
                    </form>
                @endif
            </div>

            <div class="space-y-3 max-h-96 overflow-y-auto">
                @php
                    $notifications = auth()->user()->notifications()->take(10)->get();
                @endphp
                
                @forelse($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $type = $data['type'] ?? 'unknown';
                        $activityData = $data['data'] ?? [];
                        $isUnread = is_null($notification->read_at);
                    @endphp
                    
                    <div class="flex items-start justify-between p-4 rounded-xl transition-all duration-200 {{ $isUnread ? 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800' : 'bg-slate-50 dark:bg-slate-700/30 border border-transparent' }} hover:shadow-md">
                        <div class="flex items-start gap-4 flex-1">
                            <!-- Icon based on notification type -->
                            @switch($type)
                                @case('user_login')
                                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-sign-in-alt text-green-600"></i>
                                    </div>
                                    @break
                                @case('user_registration')
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user-plus text-blue-600"></i>
                                    </div>
                                    @break
                                @case('user_logout')
                                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-sign-out-alt text-gray-600"></i>
                                    </div>
                                    @break
                                @case('payment_success')
                                    <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-check-circle text-emerald-600"></i>
                                    </div>
                                    @break
                                @case('payment_failed')
                                    <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-times-circle text-red-600"></i>
                                    </div>
                                    @break
                                @case('course_access')
                                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-book-open text-purple-600"></i>
                                    </div>
                                    @break
                                @case('file_download')
                                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-download text-indigo-600"></i>
                                    </div>
                                    @break
                                @case('profile_update')
                                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user-edit text-yellow-600"></i>
                                    </div>
                                    @break
                                @default
                                    <div class="w-10 h-10 bg-slate-100 dark:bg-slate-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-info text-slate-600"></i>
                                    </div>
                            @endswitch

                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-slate-800 dark:text-white text-sm">
                                    {{ $activityData['message'] ?? 'Activité utilisateur' }}
                                </p>
                                <div class="flex items-center gap-3 mt-1">
                                    <p class="text-xs text-slate-500 font-medium">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                    @if(isset($activityData['user_email']))
                                        <span class="text-xs text-slate-400">•</span>
                                        <p class="text-xs text-slate-500 truncate">{{ $activityData['user_email'] }}</p>
                                    @endif
                                </div>
                                
                                <!-- Additional info for specific types -->
                                @if($type === 'payment_success' || $type === 'payment_failed')
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="text-xs font-bold {{ $type === 'payment_success' ? 'text-emerald-600' : 'text-red-600' }} bg-{{ $type === 'payment_success' ? 'emerald' : 'red' }}-50 dark:bg-{{ $type === 'payment_success' ? 'emerald' : 'red' }}-900/20 px-2 py-1 rounded">
                                            {{ number_format($activityData['amount'] ?? 0, 0) }} Ar
                                        </span>
                                        @if(isset($activityData['provider']))
                                            <span class="text-xs text-slate-500 bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded">
                                                {{ ucfirst($activityData['provider']) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($isUnread)
                            <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST" class="ml-4">
                                @csrf
                                <button type="submit" class="text-blue-600 hover:text-blue-700 text-xs font-medium">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12">
                        <i class="fas fa-bell-slash text-5xl text-slate-300 mb-4"></i>
                        <p class="text-slate-500 font-medium" id="no-notifications">Aucune notification pour le moment</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Activité récente -->
        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-8 mb-10">
            <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-3">
                <i class="fas fa-history text-indigo-500"></i> <span id="recent-activity-title">Activité Récente</span>
            </h2>
            <div class="space-y-3">
                @forelse($data['recent_activity'] as $activity)
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/30 rounded-xl hover:bg-white dark:hover:bg-slate-700 transition-colors border border-transparent hover:border-slate-200 dark:hover:border-slate-600">
                        <div class="flex items-center">
                            @if($activity['type'] === 'payment')
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-4 shadow-sm">
                                    <i class="fas fa-check text-green-600"></i>
                                </div>
                            @else
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-4 shadow-sm">
                                    <i class="fas fa-info text-blue-600"></i>
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-slate-800 dark:text-white text-sm">{{ $activity['description'] }}</p>
                                <p class="text-xs text-slate-500 font-medium">{{ $activity['created_at']->diffForHumans() }}</p>
                            </div>
                        </div>
                        @if(isset($activity['amount']))
                            <span class="font-bold text-green-600 bg-green-50 dark:bg-green-900/20 px-3 py-1 rounded-lg border border-green-100 dark:border-green-900/50">{{ number_format($activity['amount'], 0) }} Ar</span>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-4xl text-slate-300 mb-3"></i>
                        <p class="text-slate-500 font-medium" id="no-activity">Aucune activité récente trouvée</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Métriques clés -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-6">
                <h3 class="text-lg font-bold text-slate-700 dark:text-slate-200 mb-4" id="metric-match-title">Taux d'Appariement</h3>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black text-blue-600">{{ number_format($data['match_rate'], 1) }}%</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full mt-4 overflow-hidden">
                     <div class="bg-blue-600 h-full rounded-full" style="width: {{ $data['match_rate'] }}%"></div>
                </div>
                <p class="text-xs text-slate-500 mt-2 font-medium" id="metric-match-desc">Rapprochement bancaire auto</p>
            </div>
            
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-6">
                <h3 class="text-lg font-bold text-slate-700 dark:text-slate-200 mb-4" id="metric-daily-title">Paiements du Jour</h3>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black text-green-600">{{ $data['pending_payments'] }}</span>
                </div>
                <p class="text-xs text-slate-500 mt-2 font-medium" id="metric-daily-desc">En attente de traitement manuel</p>
            </div>
            
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-6">
                <h3 class="text-lg font-bold text-slate-700 dark:text-slate-200 mb-4" id="metric-active-title">Sessions Actives</h3>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black text-purple-600">{{ $data['total_sessions'] }}</span>
                </div>
                <p class="text-xs text-slate-500 mt-2 font-medium" id="metric-active-desc">Examens et regroupements en cours</p>
            </div>
        </div>
    </div>
@endsection