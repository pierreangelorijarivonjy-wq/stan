<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div
                class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                <i class="fas fa-user-circle text-2xl"></i>
            </div>
            <div>
                <h2 class="font-black text-3xl text-white leading-tight">
                    {{ __('Mon Profil') }}
                </h2>
                <p class="text-slate-500 text-sm font-medium mt-1">Gérez vos informations personnelles et la sécurité de
                    votre compte.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Profile Info Section -->
        <div class="relative group">
            <div
                class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-[2.5rem] opacity-10 group-hover:opacity-20 blur transition duration-500">
            </div>
            <div class="relative p-8 sm:p-12 bg-[#1E293B]/50 backdrop-blur-xl border border-white/5 rounded-[2.5rem]">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Password Update Section -->
        <div class="relative group">
            <div
                class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-[2.5rem] opacity-10 group-hover:opacity-20 blur transition duration-500">
            </div>
            <div class="relative p-8 sm:p-12 bg-[#1E293B]/50 backdrop-blur-xl border border-white/5 rounded-[2.5rem]">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- 2FA Section -->
        <div class="relative group">
            <div
                class="absolute -inset-0.5 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-[2.5rem] opacity-10 group-hover:opacity-20 blur transition duration-500">
            </div>
            <div class="relative p-8 sm:p-12 bg-[#1E293B]/50 backdrop-blur-xl border border-white/5 rounded-[2.5rem]">
                <div class="max-w-xl">
                    @include('profile.partials.two-factor-authentication-form')
                </div>
            </div>
        </div>

        <!-- Delete Account Section -->
        <div class="relative group">
            <div
                class="absolute -inset-0.5 bg-gradient-to-r from-rose-500 to-red-500 rounded-[2.5rem] opacity-10 group-hover:opacity-20 blur transition duration-500">
            </div>
            <div class="relative p-8 sm:p-12 bg-[#1E293B]/50 backdrop-blur-xl border border-white/5 rounded-[2.5rem]">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>