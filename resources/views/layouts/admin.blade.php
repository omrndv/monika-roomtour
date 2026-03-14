<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Monika Roomtour</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-slate-50">
    <nav class="bg-indigo-950 text-white p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-500 p-2 rounded-lg">
                    <i class="fas fa-vr-cardboard"></i>
                </div>
                <h1 class="text-lg font-bold tracking-tight hidden lg:block">MONIKA <span class="text-indigo-400">ROOMTOUR</span></h1>
            </div>

            <div class="flex items-center bg-white/5 p-1 rounded-2xl border border-white/10">
                <a href="{{ route('dashboard') }}"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center {{ request()->routeIs('dashboard') ? 'bg-indigo-500 text-white shadow-lg' : 'text-indigo-300 hover:text-white' }}">
                    <i class="fas fa-list-ul mr-2"></i> LIST
                </a>
                <a href="{{ route('dashboard.grid') }}"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center {{ request()->routeIs('dashboard.grid') ? 'bg-indigo-500 text-white shadow-lg' : 'text-indigo-300 hover:text-white' }}">
                    <i class="fas fa-calendar-alt mr-2"></i> JADWAL MINGGUAN
                </a>
            </div>

            <div class="flex items-center space-x-4">
                <div class="text-right hidden sm:block">
                    <p class="text-[10px] text-indigo-300 uppercase font-bold">Admin</p>
                    <p class="text-sm font-semibold">Monika</p>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white p-2.5 rounded-xl transition">
                        <i class="fas fa-power-off"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="py-8">
        @yield('content')
    </main>

    <footer class="text-center py-8 text-slate-400 text-xs">
        &copy; 2026 Monika Roomtour Management. Built for Nadiv.
    </footer>

    @stack('scripts')
</body>

</html>