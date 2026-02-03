<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                    <i class="fas fa-paper-plane text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ __('Nouvelle Communication') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Créez et diffusez un message officiel aux
                        étudiants.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.communications.index') }}"
                    class="inline-flex items-center gap-2 bg-white/5 border border-white/10 text-white px-6 py-3 rounded-2xl font-black hover:bg-white/10 transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="relative group">
            <div
                class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-[2.5rem] opacity-10 blur transition duration-500">
            </div>
            <div class="relative glass p-8 sm:p-10 rounded-[2.5rem] border border-white/5 shadow-2xl">
                <form action="{{ route('admin.communications.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Type
                                de communication</label>
                            <div class="relative group/select">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-tag text-slate-600 text-sm group-focus-within/select:text-indigo-400 transition-colors"></i>
                                </div>
                                <select name="type" required
                                    class="block w-full pl-12 pr-10 py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none font-medium cursor-pointer">
                                    <option value="announcement" class="bg-[#1E293B]">Annonce Générale</option>
                                    <option value="convocation" class="bg-[#1E293B]">Notification de Convocation
                                    </option>
                                    <option value="calendar" class="bg-[#1E293B]">Calendrier des Examens</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-600">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Cible</label>
                            <div class="relative group/select">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-users text-slate-600 text-sm group-focus-within/select:text-indigo-400 transition-colors"></i>
                                </div>
                                <select name="target" required
                                    class="block w-full pl-12 pr-10 py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none font-medium cursor-pointer">
                                    <option value="all" class="bg-[#1E293B]">Tous les étudiants</option>
                                    <option value="paid" class="bg-[#1E293B]">Étudiants ayant payé</option>
                                    <option value="unpaid" class="bg-[#1E293B]">Étudiants en retard de paiement</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-600">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Titre /
                            Objet</label>
                        <div class="relative group/input">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i
                                    class="fas fa-heading text-slate-600 text-sm group-focus-within/input:text-indigo-400 transition-colors"></i>
                            </div>
                            <input type="text" name="title" required
                                class="block w-full pl-12 pr-4 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium"
                                placeholder="Ex: Disponibilité des convocations - Session Janvier 2026">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Contenu du
                            message</label>
                        <textarea name="content" rows="6" required
                            class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium h-48"
                            placeholder="Saisissez votre message ici..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Canaux
                                d'envoi</label>
                            <div class="flex flex-wrap gap-4">
                                <label
                                    class="relative flex items-center gap-3 px-4 py-3 bg-white/5 border border-white/10 rounded-xl cursor-pointer hover:bg-white/10 transition-all group/check">
                                    <input type="checkbox" name="channels[]" value="in_app" checked
                                        class="w-4 h-4 rounded border-white/10 bg-white/5 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-0 transition-all">
                                    <span
                                        class="text-sm font-bold text-slate-300 group-hover/check:text-white">In-App</span>
                                </label>
                                <label
                                    class="relative flex items-center gap-3 px-4 py-3 bg-white/5 border border-white/10 rounded-xl cursor-pointer hover:bg-white/10 transition-all group/check">
                                    <input type="checkbox" name="channels[]" value="email"
                                        class="w-4 h-4 rounded border-white/10 bg-white/5 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-0 transition-all">
                                    <span
                                        class="text-sm font-bold text-slate-300 group-hover/check:text-white">Email</span>
                                </label>
                                <label
                                    class="relative flex items-center gap-3 px-4 py-3 bg-white/5 border border-white/10 rounded-xl cursor-pointer hover:bg-white/10 transition-all group/check">
                                    <input type="checkbox" name="channels[]" value="sms"
                                        class="w-4 h-4 rounded border-white/10 bg-white/5 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-0 transition-all">
                                    <span
                                        class="text-sm font-bold text-slate-300 group-hover/check:text-white">SMS</span>
                                </label>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Programmation
                                (Optionnel)</label>
                            <div class="relative group/input">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-clock text-slate-600 text-sm group-focus-within/input:text-indigo-400 transition-colors"></i>
                                </div>
                                <input type="datetime-local" name="scheduled_at"
                                    class="block w-full pl-12 pr-4 py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium">
                            </div>
                            <p class="text-[10px] text-slate-500 mt-2 italic ml-4">Laissez vide pour un envoi immédiat
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-6 pt-8 border-t border-white/5">
                        <a href="{{ route('admin.communications.index') }}"
                            class="text-sm font-black text-slate-500 hover:text-white transition-colors uppercase tracking-widest">Annuler</a>
                        <button type="submit"
                            class="bg-indigo-500 text-white px-10 py-4 rounded-2xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20 flex items-center gap-3">
                            <i class="fas fa-paper-plane text-xs"></i>
                            Enregistrer & Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>