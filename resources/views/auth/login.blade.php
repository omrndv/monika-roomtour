<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monika Roomtour</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>

<body class="bg-indigo-950 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md border-t-8 border-indigo-500 transform transition-all hover:scale-[1.01]">
        <div class="text-center mb-8">
            <div class="bg-indigo-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 rotate-3">
                <i class="fas fa-lock text-indigo-600 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Owner Access</h1>
            <p class="text-slate-500 text-sm mt-1">Silahkan login untuk kelola Roomtour</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            @if($errors->has('auth_error'))
            <div class="bg-red-50 text-red-600 p-4 rounded-xl text-xs font-bold border border-red-100 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $errors->first('auth_error') }}
            </div>
            @endif

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Username</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                        <i class="fas fa-user text-sm"></i>
                    </span>
                    <input type="text" name="username" required
                        class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none transition-all"
                        placeholder="Username admin">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                        <i class="fas fa-key text-sm"></i>
                    </span>
                    
                    <input type="password" name="password" id="passwordInput" required
                        class="w-full pl-11 pr-12 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none transition-all"
                        placeholder="••••••••">
                    
                    <button type="button" onclick="togglePassword()" 
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-indigo-600 transition-colors">
                        <i id="eyeIcon" class="fas fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all duration-200">
                MASUK KE DASHBOARD
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-50 text-center">
            <p class="text-[10px] font-bold text-slate-300 uppercase tracking-tighter">© 2026 Monika Roomtour</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>

</body>
</html>