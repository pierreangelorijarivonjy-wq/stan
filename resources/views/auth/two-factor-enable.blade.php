@extends('layouts.app')

@section('content')
    <div class="min-h-[80vh] flex flex-col items-center justify-center px-4 py-12">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div
                    class="mx-auto w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-[2rem] flex items-center justify-center text-white shadow-2xl shadow-indigo-500/20 mb-8 transform hover:rotate-12 transition-transform duration-500">
                    <i class="fas fa-shield-alt text-3xl"></i>
                </div>
                <h2 class="text-4xl font-black text-white tracking-tight">Activer le 2FA</h2>
                <p class="text-slate-500 mt-3 font-medium">Sécurisez votre compte avec l'authentification à deux facteurs.
                </p>
            </div>

            <div class="glass rounded-[3rem] p-8 sm:p-10 shadow-2xl relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>

                <div class="relative space-y-8">
                    <div class="text-sm text-slate-400 text-center leading-relaxed">
                        Scannez ce code QR avec votre application d'authentification (Google Authenticator, Authy, etc.).
                    </div>

                    <!-- QR Code Frame -->
                    <div
                        class="flex justify-center p-6 bg-white rounded-[2.5rem] shadow-2xl shadow-white/5 border border-white/10 group">
                        <div class="p-4 bg-white rounded-2xl group-hover:scale-105 transition-transform duration-500">
                            {!! $qrCode !!}
                        </div>
                    </div>

                    <!-- Secret Key Display -->
                    <div class="bg-white/5 rounded-2xl p-5 border border-white/10 group hover:bg-white/10 transition-all">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3">
                            Clé secrète (si le scan échoue)
                        </p>
                        <code class="block text-sm font-mono text-indigo-400 break-all select-all font-bold">
                                {{ $secret }}
                            </code>
                    </div>

                    <form method="POST" action="{{ route('two-factor.confirm') }}" class="space-y-6">
                        @csrf

                        <div class="space-y-2">
                            <label for="code"
                                class="block text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Code de
                                vérification</label>
                            <input id="code" name="code" type="text" required autofocus
                                class="block w-full px-4 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-center text-2xl font-black tracking-[0.5em]"
                                placeholder="000000" autocomplete="off">
                            @error('code')
                                <p class="mt-2 text-xs text-rose-500 font-bold text-center">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/20 hover:shadow-indigo-500/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group">
                            <span>Confirmer et Activer</span>
                            <i class="fas fa-check-circle text-sm group-hover:scale-110 transition-transform"></i>
                        </button>

                        <div class="text-center">
                            <a href="{{ route('profile.edit') }}"
                                class="text-sm font-bold text-slate-500 hover:text-white transition-colors">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection