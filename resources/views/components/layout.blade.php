<!DOCTYPE html>
<html lang="en" x-data="themeSwitcher()" x-init="init()">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' - Chirper' : 'Chirper' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col bg-base-200 font-sans" :data-theme="theme">

    <!-- NAVBAR -->
    <nav class="navbar bg-base-100 shadow-sm">
        <div class="navbar-start">
            <a href="/" class="btn btn-ghost text-xl gap-2">
                <!-- Imagem como símbolo -->
                <img src="/binladabrksedu.jpeg" alt="Logo" class="w-8 h-8 rounded-full object-cover">
                Binlada
            </a>
            </div>
            <div class="navbar-center">
                <!-- Botão de tema corrigido -->
                <button @click="toggleTheme" class="btn btn-ghost btn-sm gap-2">
                    <span x-show="theme === 'dark'">☀️ Modo Claro</span>
                    <span x-show="theme !== 'dark'">🌙 Modo Escuro</span>
                </button>
        </div>
        <div class="navbar-end gap-2">
            @auth
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                        <div class="w-10 rounded-full">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar">
                            @else
                                <div class="bg-primary text-primary-content flex items-center justify-center w-full h-full">
                                    <span class="text-sm font-bold">{{ substr(auth()->user()->name, 0, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    </label>
                    <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                        <li class="menu-title">
                            <span>{{ auth()->user()->name }}</span>
                        </li>
                        <li><a href="{{ route('profile.edit') }}" class="gap-2">👤 Meu Perfil</a></li>
                        <li>
                            <hr class="my-1">
                        </li>
                        <li>
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="w-full text-left gap-2">🚪 Sair</button>
                                </form>
                        </li>
                        </ul>
                        </div>
            @else
                <a href="/login" class="btn btn-ghost btn-sm">Sign In</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Sign Up</a>
            @endauth
        </div>
    </nav>

    @if (session('success'))
        <div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 animate-fade-out pointer-events-none">
            <div class="alert alert-success shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 animate-fade-out pointer-events-none">
            <div class="alert alert-error shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <main class="flex-1 container mx-auto px-4 py-8">
        {{ $slot }}
    </main>

    <footer class="footer footer-center p-5 bg-base-300 text-base-content text-xs">
        <div>
            <p>© 2025 Binlada - Built with Surra and Edu</p>
        </div>
    </footer>

    <script>
        function themeSwitcher() {
            return {
                theme: 'lofi',
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    if (savedTheme && this.isValidTheme(savedTheme)) {
                        this.theme = savedTheme;
                    } else {
                        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                        this.theme = prefersDark ? 'dark' : 'lofi';
                    }
                    this.applyTheme();
                },
                toggleTheme() {
                    this.theme = this.theme === 'dark' ? 'lofi' : 'dark';
                    this.applyTheme();
                    localStorage.setItem('theme', this.theme);
                },
                applyTheme() {
                    document.documentElement.setAttribute('data-theme', this.theme);
                },
                isValidTheme(theme) {
                    const validThemes = ['lofi', 'dark', 'light', 'cupcake', 'bumblebee', 'emerald'];
                    return validThemes.includes(theme);
                }
            }
        }
    </script>
</body>

</html>