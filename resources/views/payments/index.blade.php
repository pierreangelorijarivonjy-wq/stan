@extends('layouts.app')

@section('content')
    <div class="space-y-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h1 class="text-4xl font-black text-white tracking-tight">Espace Paiements</h1>
                <p class="text-slate-500 mt-2 font-medium">Gérez vos frais institutionnels en toute sécurité</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('payments.history') }}" class="px-6 py-3 bg-white/5 border border-white/10 rounded-2xl font-bold text-slate-300 hover:text-white hover:bg-white/10 transition-all flex items-center gap-2">
                    <i class="fas fa-history"></i>
                    Historique complet
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Left: Card & Quick Actions -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Premium Card Visual -->
                <div class="relative aspect-[1.586/1] rounded-[2.5rem] overflow-hidden shadow-2xl group">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-900 opacity-90"></div>
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
                    
                    <div class="relative z-10 p-8 flex flex-col justify-between h-full text-white">
                        <div class="flex justify-between items-start">
                            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="h-10 w-auto brightness-0 invert opacity-80">
                            <i class="fas fa-wifi text-xl opacity-50 rotate-90"></i>
                        </div>

                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60 mb-1">Solde à régler</p>
                            <p class="text-4xl font-black tracking-tight">185 000 <span class="text-lg font-medium opacity-60">Ar</span></p>
                        </div>

                        <div class="flex justify-between items-end">
                            <div class="min-w-0">
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60 mb-1">Titulaire</p>
                                <p class="font-bold truncate">{{ auth()->user()->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60 mb-1">Statut</p>
                                <span class="px-2 py-0.5 bg-white/20 backdrop-blur-md rounded-lg text-[10px] font-black uppercase">Actif</span>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative Blurs -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-400/30 rounded-full blur-3xl -mr-10 -mt-10"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-indigo-400/30 rounded-full blur-3xl -ml-5 -mb-5"></div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-[#1E293B]/50 backdrop-blur-xl border border-white/5 p-8 rounded-[2.5rem] space-y-6">
                    <h3 class="text-lg font-bold text-white flex items-center gap-3">
                        <i class="fas fa-bolt text-amber-400"></i>
                        Actions Rapides
                    </h3>
                    <div class="grid grid-cols-1 gap-4">
                        <a href="{{ route('payment.choose') }}" class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-2xl font-black text-center shadow-xl shadow-indigo-500/20 hover:shadow-indigo-500/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group">
                            <i class="fas fa-plus-circle text-lg group-hover:rotate-90 transition-transform"></i>
                            Nouveau Paiement
                        </a>
                        <button class="w-full py-4 bg-white/5 border border-white/10 text-slate-300 rounded-2xl font-bold hover:bg-white/10 hover:text-white transition-all flex items-center justify-center gap-3">
                            <i class="fas fa-headset"></i>
                            Assistance Paiement
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right: History & Proofs -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Proof Upload -->
                <div class="bg-[#1E293B]/50 backdrop-blur-xl border border-white/5 p-8 rounded-[2.5rem]">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-xl font-bold text-white flex items-center gap-3">
                            <i class="fas fa-university text-indigo-400"></i>
                            Virement / Dépôt Bancaire
                        </h3>
                    </div>

                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="bg-indigo-500/5 border border-indigo-500/10 rounded-2xl p-6 space-y-4">
                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Coordonnées Institutionnelles</p>
                            <div class="space-y-2 text-sm">
                                <p class="flex justify-between"><span class="text-slate-500">Banque :</span> <span class="text-white font-bold">BNI Madagascar</span></p>
                                <p class="flex justify-between"><span class="text-slate-500">Compte :</span> <span class="text-white font-bold font-mono">00001 00002 000...</span></p>
                                <p class="flex justify-between"><span class="text-slate-500">Titulaire :</span> <span class="text-white font-bold">EduPass-MG</span></p>
                            </div>
                            <p class="text-[10px] italic text-slate-500 mt-4">Note : Utilisez votre matricule comme référence.</p>
                        </div>

                        <div>
                            @if(auth()->user()->payments()->where('status', 'pending')->where('provider', 'transfer')->exists())
                                <form action="{{ route('payments.upload-proof') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Paiement concerné</label>
                                        <select name="payment_id" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-medium appearance-none">
                                            @foreach(auth()->user()->payments()->where('status', 'pending')->where('provider', 'transfer')->get() as $p)
                                                <option value="{{ $p->id }}">{{ ucfirst($p->type) }} - {{ number_format($p->amount, 0, ',', ' ') }} Ar</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Preuve (PDF/Image)</label>
                                        <input type="file" name="proof" required class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-indigo-500/20 file:text-indigo-400 hover:file:bg-indigo-500/30 transition-all">
                                    </div>
                                    <button type="submit" class="w-full py-3 bg-indigo-500 text-white rounded-xl font-black hover:bg-indigo-600 transition-all flex items-center justify-center gap-2">
                                        <i class="fas fa-upload"></i> Envoyer
                                    </button>
                                </form>
                            @else
                                <div class="h-full flex flex-col items-center justify-center text-center p-6 border-2 border-dashed border-white/5 rounded-2xl">
                                    <i class="fas fa-info-circle text-slate-600 text-2xl mb-3"></i>
                                    <p class="text-slate-500 text-sm">Aucun virement en attente de preuve.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="bg-[#1E293B]/50 backdrop-blur-xl border border-white/5 rounded-[2.5rem] overflow-hidden">
                    <div class="p-8 border-b border-white/5 flex justify-between items-center">
                        <h3 class="text-xl font-bold text-white flex items-center gap-3">
                            <i class="fas fa-history text-purple-400"></i>
                            Transactions Récentes
                        </h3>
                    </div>

                    <div class="p-4 space-y-2">
                        @forelse(auth()->user()->payments()->latest()->take(5)->get() as $payment)
                            <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-white/5 transition-all group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl {{ $payment->status === 'paid' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400' }} flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                                        <i class="fas {{ $payment->status === 'paid' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-white">{{ ucfirst(str_replace('_', ' ', $payment->type)) }}</p>
                                        <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest">{{ $payment->created_at->format('d M Y') }} • {{ strtoupper($payment->provider) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-black text-white">{{ number_format($payment->amount, 0, ',', ' ') }} Ar</p>
                                    <span class="inline-block px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $payment->status === 'paid' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400' }}">
                                        {{ $payment->status === 'paid' ? 'Validé' : 'En attente' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center">
                                <p class="text-slate-500 italic">Aucune transaction trouvée.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection