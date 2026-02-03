<x-app-layout>
    <div class="space-y-8">
        
        <!-- Premium Hero Section -->
        <div class="relative overflow-hidden rounded-[2rem] group">
            <!-- Background Gradient & Pattern -->
            <div class="absolute inset-0 bg-gradient-to-r from-premium-sidebar via-premium-purple to-premium-sidebar animate-gradient-x opacity-90"></div>
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
            
            <!-- Content -->
            <div class="relative z-10 p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="space-y-4 text-center md:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full border border-white/10 text-white text-xs font-bold tracking-widest uppercase">
                        <span class="w-2 h-2 bg-premium-cyan rounded-full animate-pulse"></span>
                        Système Opérationnel
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight">
                        Bienvenue, <span class="text-premium-cyan">{{ auth()->user()->name }}</span> ! 👋
                    </h1>
                    <p class="text-indigo-100 text-lg font-medium opacity-90 max-w-2xl">
                        Nous sommes le {{ now()->locale('fr')->isoFormat('LL') }}. Prêt pour une journée productive ?
                    </p>
                </div>
                
                <!-- Quick Action Dropdown -->
                <div class="flex-shrink-0 relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="px-8 py-4 bg-white text-premium-sidebar rounded-2xl font-black shadow-2xl shadow-white/10 hover:scale-105 transition-all flex items-center gap-3">
                        <i class="fas fa-bolt text-premium-purple"></i>
                        Actions Rapides
                        <i class="fas fa-chevron-down text-xs ml-2"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl overflow-hidden z-20" 
                         style="display: none;">
                        <div class="p-2 space-y-1">
                            @if(auth()->user()->hasRole('admin'))
                                <a href="{{ route('register') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 rounded-xl transition-colors text-slate-700 font-bold text-sm">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center"><i class="fas fa-user-plus"></i></div>
                                    Nouvel Utilisateur
                                </a>
                            @endif
                            @if(auth()->user()->hasAnyRole(['admin', 'scolarite']))
                                <a href="{{ route('convocations.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 rounded-xl transition-colors text-slate-700 font-bold text-sm">
                                    <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center"><i class="fas fa-file-alt"></i></div>
                                    Générer Convocation
                                </a>
                            @endif
                            @if(auth()->user()->hasAnyRole(['admin', 'comptable']))
                                <a href="{{ route('admin.reconciliation.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 rounded-xl transition-colors text-slate-700 font-bold text-sm">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fas fa-sync"></i></div>
                                    Rapprochement
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-premium-cyan/20 rounded-full blur-3xl -ml-10 -mb-10 pointer-events-none"></div>
        </div>

        <!-- Role-Specific Content -->
        <div class="space-y-12">
            
            <!-- ADMIN / ADMIN IT DASHBOARD -->
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('admin_it'))
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach([
                        ['label' => 'Utilisateurs', 'value' => \App\Models\User::count(), 'icon' => 'fa-users', 'color' => 'indigo', 'trend' => '+12%'],
                        ['label' => 'Score Moyen', 'value' => round(\App\Models\User::avg('trust_score') ?? 0) . '%', 'icon' => 'fa-shield-heart', 'color' => 'emerald', 'trend' => 'Confiance'],
                        ['label' => 'Logs d\'Audit', 'value' => \App\Models\AuditLog::count(), 'icon' => 'fa-shield-alt', 'color' => 'amber', 'trend' => '+5%'],
                        ['label' => 'Requêtes Staff', 'value' => \App\Models\User::where('status', 'pending')->count(), 'icon' => 'fa-user-clock', 'color' => 'rose', 'trend' => 'Alert'],
                    ] as $stat)
                        <div class="bg-premium-sidebar/30 backdrop-blur-xl border border-white/5 p-6 rounded-[2rem] hover:bg-white/5 transition-all group relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i class="fas {{ $stat['icon'] }} text-6xl text-{{ $stat['color'] }}-400"></i>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-{{ $stat['color'] }}-500/10 flex items-center justify-center text-{{ $stat['color'] }}-400 mb-4 group-hover:scale-110 transition-transform">
                                <i class="fas {{ $stat['icon'] }} text-xl"></i>
                            </div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">{{ $stat['label'] }}</p>
                            <div class="flex items-end gap-3">
                                <p class="text-3xl font-black text-white">{{ $stat['value'] }}</p>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-{{ $stat['color'] }}-500/20 text-{{ $stat['color'] }}-300 mb-1.5">
                                    {{ $stat['trend'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Live Activity Widget -->
                        <div class="bg-premium-sidebar/30 backdrop-blur-xl border border-white/5 rounded-[2.5rem] p-8">
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="text-xl font-bold text-white flex items-center gap-3">
                                    <div class="relative">
                                        <i class="fas fa-satellite-dish text-premium-cyan"></i>
                                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-rose-500 rounded-full animate-ping"></span>
                                    </div>
                                    Live Dashboard & Activités
                                </h3>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-emerald-400 uppercase bg-emerald-500/10 px-2 py-1 rounded-lg border border-emerald-500/20">En direct</span>
                                    <a href="{{ route('admin.audit-logs.index') }}" class="text-xs font-bold text-slate-500 hover:text-white transition-colors">Historique complet</a>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                @foreach(\App\Models\AuditLog::with('user')->latest()->take(8)->get() as $log)
                                    <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all group">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-slate-700/50 flex items-center justify-center text-slate-400 text-sm group-hover:bg-premium-cyan/20 group-hover:text-premium-cyan transition-colors">
                                                <i class="fas {{ $log->event === 'login' ? 'fa-sign-in-alt' : ($log->event === 'payment' ? 'fa-wallet' : 'fa-bolt') }}"></i>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <p class="text-sm font-bold text-white">{{ $log->description }}</p>
                                                    @if($log->user && $log->user->trust_score < 30)
                                                        <span class="w-2 h-2 bg-rose-500 rounded-full" title="Score de confiance bas"></span>
                                                    @endif
                                                </div>
                                                <p class="text-[10px] text-slate-500 uppercase font-black">
                                                    {{ $log->user ? $log->user->name : 'Système' }} • {{ $log->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right hidden sm:block">
                                            <div class="text-[10px] font-mono text-slate-500">{{ $log->ip_address }}</div>
                                            <div class="text-[8px] text-slate-600 truncate max-w-[100px]">{{ $log->user_agent }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Real-time Stats Mockup -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white/5 rounded-[2rem] p-6 border border-white/5">
                                <h4 class="text-sm font-bold text-slate-400 mb-4 uppercase tracking-widest">Trafic par Appareil</h4>
                                <div class="flex items-center justify-around py-4">
                                    <div class="text-center">
                                        <i class="fas fa-desktop text-2xl text-indigo-400 mb-2"></i>
                                        <p class="text-xl font-black text-white">65%</p>
                                        <p class="text-[10px] text-slate-500 font-bold">Desktop</p>
                                    </div>
                                    <div class="text-center">
                                        <i class="fas fa-mobile-alt text-2xl text-premium-purple mb-2"></i>
                                        <p class="text-xl font-black text-white">35%</p>
                                        <p class="text-[10px] text-slate-500 font-bold">Mobile</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white/5 rounded-[2rem] p-6 border border-white/5">
                                <h4 class="text-sm font-bold text-slate-400 mb-4 uppercase tracking-widest">Paiements en cours</h4>
                                <div class="flex items-center gap-4">
                                    <div class="flex-1">
                                        <div class="flex justify-between text-[10px] font-bold text-slate-500 mb-1">
                                            <span>Mvola</span>
                                            <span>12</span>
                                        </div>
                                        <div class="w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-amber-500 h-full w-3/4"></div>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between text-[10px] font-bold text-slate-500 mb-1">
                                            <span>Orange</span>
                                            <span>4</span>
                                        </div>
                                        <div class="w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-orange-500 h-full w-1/4"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-8">
                         <!-- System Status Widget -->
                        <div class="bg-premium-sidebar/30 backdrop-blur-xl border border-white/5 rounded-[2.5rem] p-8">
                            <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                                <i class="fas fa-server text-emerald-400"></i>
                                État du Système
                            </h3>
                            <div class="space-y-6">
                                <div>
                                    <div class="flex justify-between text-xs font-bold text-slate-400 mb-2">
                                        <span>CPU Usage</span>
                                        <span class="text-emerald-400">12%</span>
                                    </div>
                                    <div class="w-full bg-white/5 rounded-full h-2">
                                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 12%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-xs font-bold text-slate-400 mb-2">
                                        <span>Memory</span>
                                        <span class="text-indigo-400">45%</span>
                                    </div>
                                    <div class="w-full bg-white/5 rounded-full h-2">
                                        <div class="bg-indigo-500 h-2 rounded-full" style="width: 45%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-xs font-bold text-slate-400 mb-2">
                                        <span>Storage</span>
                                        <span class="text-premium-cyan">28%</span>
                                    </div>
                                    <div class="w-full bg-white/5 rounded-full h-2">
                                        <div class="bg-premium-cyan h-2 rounded-full" style="width: 28%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Tools -->
                        <div class="bg-premium-sidebar/30 backdrop-blur-xl border border-white/5 rounded-[2.5rem] p-8">
                            <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                                <i class="fas fa-tools text-premium-purple"></i>
                                Administration
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <a href="{{ route('admin.users.index') }}" class="p-4 bg-white/5 rounded-2xl hover:bg-white/10 transition-all text-center group">
                                    <i class="fas fa-users text-2xl text-indigo-400 mb-2 group-hover:scale-110 transition-transform"></i>
                                    <p class="text-xs font-bold text-white">Utilisateurs</p>
                                </a>
                                <a href="{{ route('admin.staff-requests.index') }}" class="p-4 bg-white/5 rounded-2xl hover:bg-white/10 transition-all text-center group relative">
                                    @if(\App\Models\User::where('status', 'pending')->count() > 0)
                                        <span class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full"></span>
                                    @endif
                                    <i class="fas fa-user-clock text-2xl text-rose-400 mb-2 group-hover:scale-110 transition-transform"></i>
                                    <p class="text-xs font-bold text-white">Requêtes</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- COMPTABLE DASHBOARD -->
            @if(auth()->user()->hasRole('comptable'))
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach([
                        ['label' => 'Revenu Total', 'value' => number_format($data['total_revenue'] ?? 0, 0, '.', ' ') . ' Ar', 'icon' => 'fa-wallet', 'color' => 'emerald'],
                        ['label' => 'Revenu du Mois', 'value' => number_format($data['revenue_month'] ?? 0, 0, '.', ' ') . ' Ar', 'icon' => 'fa-chart-line', 'color' => 'indigo'],
                        ['label' => 'Paiements en Attente', 'value' => $data['pending_payments'] ?? 0, 'icon' => 'fa-clock', 'color' => 'amber'],
                        ['label' => 'Anomalies', 'value' => $data['anomalies_count'] ?? 0, 'icon' => 'fa-exclamation-circle', 'color' => 'rose']
                    ] as $stat)
                        <div class="bg-premium-sidebar/30 backdrop-blur-xl border border-white/5 p-6 rounded-[2rem] hover:bg-white/5 transition-all group">
                            <div class="w-12 h-12 rounded-xl bg-{{ $stat['color'] }}-500/10 flex items-center justify-center text-{{ $stat['color'] }}-400 mb-4 group-hover:scale-110 transition-transform">
                                <i class="fas {{ $stat['icon'] }} text-xl"></i>
                            </div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">{{ $stat['label'] }}</p>
                            <p class="text-2xl font-black text-white">{{ $stat['value'] }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 bg-premium-sidebar/30 backdrop-blur-xl border border-white/5 rounded-[2.5rem] p-8">
                        <h3 class="text-xl font-bold text-white mb-8 flex items-center gap-3">
                            <i class="fas fa-list text-indigo-400"></i>
                            Derniers Paiements
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-slate-500 text-[10px] font-black uppercase tracking-widest border-b border-white/5">
                                        <th class="pb-4 pl-4">Étudiant</th>
                                        <th class="pb-4">Montant</th>
                                        <th class="pb-4">Type</th>
                                        <th class="pb-4 pr-4 text-right">Statut</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach($data['recent_payments'] ?? [] as $payment)
                                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                            <td class="py-4 pl-4 font-bold text-white">{{ $payment->user->name }}</td>
                                            <td class="py-4 text-slate-300">{{ number_format($payment->amount, 0, '.', ' ') }} Ar</td>
                                            <td class="py-4 text-slate-400 uppercase text-[10px] font-black">{{ $payment->type }}</td>
                                            <td class="py-4 pr-4 text-right">
                                                <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase {{ $payment->status === 'paid' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400' }}">
                                                    {{ $payment->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bg-premium-sidebar/30 backdrop-blur-xl border border-white/5 rounded-[2.5rem] p-8">
                        <h3 class="text-xl font-bold text-white mb-8 flex items-center gap-3">
                            <i class="fas fa-tools text-premium-purple"></i>
                            Outils Comptables
                        </h3>
                        <div class="space-y-4">
                            <a href="{{ route('admin.reconciliation.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 hover:bg-white/10 transition-all group">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-400 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-sync"></i>
                                </div>
                                <span class="text-sm font-bold text-white">Rapprochement Bancaire</span>
                            </a>
                            <a href="{{ route('admin.reconciliation.exceptions') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 hover:bg-white/10 transition-all group">
                                <div class="w-10 h-10 rounded-xl bg-rose-500/20 flex items-center justify-center text-rose-400 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <span class="text-sm font-bold text-white">Gérer les Exceptions</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- SCOLARITE DASHBOARD -->
            @if(auth()->user()->hasRole('scolarite'))
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach([
                        ['label' => 'Étudiants Actifs', 'value' => $data['total_students'] ?? 0, 'icon' => 'fa-user-graduate', 'color' => 'indigo'],
                        ['label' => 'Sessions d\'Examen', 'value' => $data['active_sessions'] ?? 0, 'icon' => 'fa-calendar-check', 'color' => 'emerald'],
                        ['label' => 'Convocations', 'value' => $data['convocations_generated'] ?? 0, 'icon' => 'fa-file-signature', 'color' => 'purple'],
                        ['label' => 'Taux de Scan', 'value' => ($data['scan_rate'] ?? 0) . '%', 'icon' => 'fa-qrcode', 'color' => 'premium-cyan']
                    ] as $stat)
                        <div class="bg-premium-sidebar/30 backdrop-blur-xl border border-white/5 p-6 rounded-[2rem] hover:bg-white/5 transition-all group">
                            <div class="w-12 h-12 rounded-xl bg-{{ $stat['color'] }}-500/10 flex items-center justify-center text-{{ $stat['color'] }}-400 mb-4 group-hover:scale-110 transition-transform">
                                <i class="fas {{ $stat['icon'] }} text-xl"></i>
                            </div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">{{ $stat['label'] }}</p>
                            <p class="text-2xl font-black text-white">{{ $stat['value'] }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 bg-premium-sidebar/30 backdrop-blur-xl border border-white/5 rounded-[2.5rem] p-8">
                        <h3 class="text-xl font-bold text-white mb-8 flex items-center gap-3">
                            <i class="fas fa-calendar text-indigo-400"></i>
                            Sessions Récentes
                        </h3>
                        <div class="space-y-4">
                            @foreach($data['recent_sessions'] ?? [] as $session)
                                <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all">
                                    <div>
                                        <p class="text-sm font-bold text-white">{{ $session->type }}</p>
                                        <p class="text-xs text-slate-500">{{ $session->date->format('d/m/Y') }} - {{ $session->center }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase bg-indigo-500/20 text-indigo-400">
                                        {{ $session->status }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="bg-premium-sidebar/30 backdrop-blur-xl border border-white/5 rounded-[2.5rem] p-8">
                        <h3 class="text-xl font-bold text-white mb-8 flex items-center gap-3">
                            <i class="fas fa-bolt text-premium-purple"></i>
                            Actions Scolarité
                        </h3>
                        <div class="space-y-4">
                            <a href="{{ route('admin.results.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 hover:bg-white/10 transition-all group">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <span class="text-sm font-bold text-white">Gérer les Résultats</span>
                            </a>
                            <a href="{{ route('admin.results.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 hover:bg-white/10 transition-all group">
                                <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-file-import"></i>
                                </div>
                                <span class="text-sm font-bold text-white">Importer Notes (CSV)</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- STUDENT DASHBOARD -->
            @if(auth()->user()->hasRole('student'))
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Progress Card -->
                    <div class="lg:col-span-2 relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-premium-cyan rounded-[2.5rem] opacity-20 group-hover:opacity-30 blur transition duration-500"></div>
                        <div class="relative bg-premium-sidebar/30 backdrop-blur-xl border border-white/5 rounded-[2.5rem] p-10">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 mb-10">
                                <div>
                                    <h3 class="text-2xl font-black text-white mb-2">Votre Progression Académique</h3>
                                    <p class="text-slate-400 font-medium">Consultez vos convocations et résultats en temps réel.</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-5xl font-black text-premium-cyan">{{ $data['convocations_count'] > 0 ? '100%' : '0%' }}</p>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Dossier</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                @foreach([
                                    ['label' => 'Paiements', 'value' => $data['total_payments'], 'icon' => 'fa-wallet', 'color' => 'indigo'],
                                    ['label' => 'Convocations', 'value' => $data['convocations_count'], 'icon' => 'fa-file-alt', 'color' => 'emerald'],
                                    ['label' => 'Sessions', 'value' => $data['upcoming_convocations']->count(), 'icon' => 'fa-calendar', 'color' => 'purple'],
                                    ['label' => 'Messages', 'value' => '0', 'icon' => 'fa-envelope', 'color' => 'amber']
                                ] as $item)
                                    <div class="bg-white/5 rounded-2xl p-4 border border-white/5 text-center hover:bg-white/10 transition-all">
                                        <div class="w-10 h-10 rounded-xl bg-{{ $item['color'] }}-500/10 flex items-center justify-center text-{{ $item['color'] }}-400 mx-auto mb-3">
                                            <i class="fas {{ $item['icon'] }}"></i>
                                        </div>
                                        <p class="text-[10px] text-slate-500 uppercase font-bold mb-1">{{ $item['label'] }}</p>
                                        <p class="text-lg font-black text-white">{{ $item['value'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Convocations -->
                    <div class="bg-premium-sidebar/30 backdrop-blur-xl border border-white/5 rounded-[2.5rem] p-8 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-8 flex items-center gap-3">
                                <i class="fas fa-id-card text-indigo-400"></i>
                                Mes Convocations
                            </h3>
                            <div class="space-y-4">
                                @forelse($data['upcoming_convocations'] as $conv)
                                    <div class="p-4 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all">
                                        <p class="text-sm font-bold text-white">{{ $conv->examSession->type }}</p>
                                        <p class="text-xs text-slate-500 mb-3">{{ $conv->examSession->date->format('d/m/Y') }}</p>
                                        <a href="{{ route('convocations.download', $conv) }}" class="text-[10px] font-black uppercase text-indigo-400 hover:text-indigo-300 transition-colors flex items-center gap-2">
                                            <i class="fas fa-download"></i> Télécharger PDF
                                        </a>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <i class="fas fa-folder-open text-slate-600 text-3xl mb-3"></i>
                                        <p class="text-slate-500 text-sm italic">Aucune convocation disponible.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <a href="{{ route('payments') }}" 
                            class="mt-10 w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-2xl font-black text-center shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40 hover:-translate-y-1 transition-all">
                            Voir mes paiements
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>