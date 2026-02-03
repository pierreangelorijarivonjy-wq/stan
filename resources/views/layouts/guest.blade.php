<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EduPass-MG') }} - Premium Experience</title>

    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(10, 11, 59, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .bg-premium-dark {
            background: radial-gradient(circle at top right, rgba(79, 70, 229, 0.1), transparent), radial-gradient(circle at bottom left, rgba(124, 58, 237, 0.1), transparent), #0F172A;
        }
    </style>
</head>

<body
    class="min-h-screen font-sans antialiased text-slate-200 flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">

    <!-- Background Image -->
    <div class="fixed inset-0 z-0">
        <img src="/images/premium-bg.png" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-premium-dark/40 backdrop-blur-[2px]"></div>
    </div>

    <div class="w-full sm:max-w-sm relative z-10">
        <!-- Logo EduPass -->
        <div class="mb-12 text-center">
            <a href="/" class="inline-flex items-center gap-4 group">
                <div
                    class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white shadow-2xl shadow-indigo-500/20 group-hover:scale-110 transition-transform">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                </div>
                <h1 class="text-3xl font-black tracking-tight text-white">EduPass<span class="text-cyan-400">-MG</span>
                </h1>
            </a>
        </div>

        <div class="glass p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden">


            <div class="relative z-10">
                {{ $slot }}
            </div>
        </div>

        <div class="mt-10 text-center text-xs text-slate-500 font-medium tracking-widest uppercase">
            &copy; 2025 EduPass-MG. Excellence Éducative.
        </div>
    </div>
    <!-- Global Chat Bubble -->
    @include('components.chat-bubble')
</body>

</html>