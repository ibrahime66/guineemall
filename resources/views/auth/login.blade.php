<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - GuinéeMall</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #0f6938 0%, #1a8f4d 50%, #0d4d2a 100%);
        }
        
        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.3);
        }
        
        .btn-primary:active {
            transform: scale(0.98);
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .slide-in-left {
            animation: slideInLeft 0.8s ease-out;
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <div class="flex-1 flex items-center justify-center p-4">
        <div class="w-full max-w-6xl flex flex-col lg:flex-row bg-white rounded-3xl shadow-2xl overflow-hidden min-h-[650px]">
        
        <!-- Panneau gauche - Design moderne -->
        <div class="hidden lg:flex lg:w-5/12 gradient-bg p-12 flex-col justify-between text-white relative overflow-hidden slide-in-left">
            <!-- Cercles décoratifs -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>
            
            <!-- Logo et nom -->
            <div class="flex items-center space-x-3 z-10">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-lg floating">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-2xl font-bold tracking-tight">GuinéeMall</span>
            </div>

            <!-- Contenu principal -->
            <div class="z-10">
                <h2 class="text-5xl font-black leading-tight mb-6">
                    Rejoignez la<br>
                    révolution du<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-300 to-green-100">e-commerce</span><br>
                    en Guinée
                </h2>
                <p class="text-green-100 text-lg leading-relaxed max-w-md">
                    Créez votre compte en quelques secondes et accédez aux meilleures offres locales.
                </p>
            </div>

        </div>

        <!-- Panneau droit - Formulaire -->
        <div class="w-full lg:w-7/12 bg-white p-8 md:p-16 flex flex-col justify-center fade-in">
            <!-- Logo mobile -->
            <div class="lg:hidden flex items-center justify-center space-x-2 mb-8">
                <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-green-700 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-800">GuinéeMall</span>
            </div>

            <div class="w-full max-w-md mx-auto">
                <!-- En-tête -->
                <div class="mb-10">
                    <h1 class="text-4xl font-black text-gray-900 mb-3">Se connecter</h1>
                    <p class="text-gray-500 text-lg">Bon retour parmi nous ! </p>
                </div>

                <!-- Message d'erreur global -->
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 text-red-600 border border-red-200 rounded-lg text-center font-bold">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Formulaire CORRIGÉ POUR LARAVEL -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div class="relative group">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Adresse Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input 
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="votreemail@exemple.com"
                                class="input-field block w-full pl-12 pr-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-2xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-green-500 focus:bg-white transition-all duration-300"
                            />
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div class="relative group">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input 
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="input-field block w-full pl-12 pr-12 py-4 bg-gray-50 border-2 border-gray-200 rounded-2xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-green-500 focus:bg-white transition-all duration-300"
                            />
                            <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Options -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer group">
                            <input 
                                type="checkbox"
                                name="remember"
                                class="w-5 h-5 rounded-lg border-2 border-gray-300 text-green-600 focus:ring-green-500 focus:ring-2 cursor-pointer transition-all"
                            />
                            <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">Se souvenir de moi</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm font-semibold text-green-600 hover:text-green-700 transition-colors">
                            Mot de passe oublié ?
                        </a>
                    </div>

                    <!-- Bouton de connexion -->
                    <button 
                        type="submit" 
                        class="btn-primary w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white py-4 rounded-2xl font-bold shadow-lg transition-all duration-300 flex items-center justify-center space-x-3 mt-8"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Se connecter</span>
                    </button>

                    <!-- Lien inscription -->
                    <div class="text-center pt-6">
                        <p class="text-gray-600">
                            Nouveau sur GuinéeMall ? 
                            <a href="{{ route('register') }}" class="font-bold text-green-600 hover:text-green-700 transition-colors ml-1">
                                Créer un compte →
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="py-4 text-center text-xs text-gray-500 uppercase tracking-widest">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4">
            <span class="text-gray-400">© GuinéeMall {{ date('Y') }}</span>
            <span class="hidden sm:inline text-gray-300">•</span>
            <a href="{{ route('home') }}" class="font-semibold text-gray-500 hover:text-green-600 transition-colors">
                Accueil
            </a>
            <a href="mailto:support@guineemall.com" class="font-semibold text-gray-500 hover:text-green-600 transition-colors">
                Support
            </a>
            <a href="{{ route('register') }}" class="font-semibold text-gray-500 hover:text-green-600 transition-colors">
                Créer un compte
            </a>
        </div>
    </footer>

    <script>
        // Fonctionnalité d'affichage/masquage du mot de passe
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Changer l'icône
            const icon = this.querySelector('svg');
            if (type === 'text') {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
            } else {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        });

        // Animation des champs au focus
        document.querySelectorAll('.input-field').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.parentElement.classList.add('text-green-500');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.parentElement.classList.remove('text-green-500');
                }
            });
        });

        // Pré-remplir l'email s'il y a une erreur
        const emailInput = document.getElementById('email');
        if (emailInput && emailInput.value.trim() !== '') {
            emailInput.parentElement.parentElement.classList.add('text-green-500');
        }
    </script>
</body>
</html>