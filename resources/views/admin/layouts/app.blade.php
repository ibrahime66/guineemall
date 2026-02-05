<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration') - GuinéeMall</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Thème Vert/Blanc Admin */
        .admin-gradient {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
        }
        .admin-gradient-text {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Animations avancées */
        .sidebar-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-item:hover {
            transform: translateX(8px);
            background: rgba(255, 255, 255, 0.1);
        }
        .sidebar-item.active {
            background: rgba(255, 255, 255, 0.15);
            border-left: 4px solid #10b981;
        }
        .floating {
            animation: floating 4s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .pulse-glow {
            animation: pulseGlow 2s ease-in-out infinite;
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.5); }
            50% { box-shadow: 0 0 40px rgba(16, 185, 129, 0.8); }
        }
        
        /* Background animé */
        .animated-bg {
            background: linear-gradient(-45deg, #f0fdf4, #dcfce7, #bbf7d0, #86efac, #4ade80, #22c55e);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Scrollbar personnalisée */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(16, 185, 129, 0.5);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(16, 185, 129, 0.7);
        }
    </style>
</head>
<body class="animated-bg min-h-screen antialiased overflow-x-hidden">

<div class="flex flex-col lg:flex-row min-h-screen">

    <!-- Sidebar Admin Moderne -->
    <aside id="adminSidebar" class="fixed inset-y-0 left-0 z-50 w-72 admin-gradient text-white shadow-2xl overflow-y-auto h-screen transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:relative">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full"></div>
            <div class="absolute bottom-20 right-10 w-24 h-24 bg-white rounded-full"></div>
            <div class="absolute top-1/2 left-1/2 w-40 h-40 bg-white rounded-full"></div>
        </div>
        
        <div class="relative z-10 p-6 flex flex-col h-full justify-between">
            <!-- Logo et Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center floating">
                        <i class="fas fa-shopping-bag text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black">Guinée<span class="text-green-200">Mall</span></h1>
                        <p class="text-green-100 text-sm">Administration</p>
                    </div>
                </div>
                
                <!-- User Info -->
                <div class="glass-effect rounded-xl p-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-shield text-white"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-green-200">Administrateur</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 space-y-2">
                <a href="{{ route('admin.dashboard') }}" 
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl text-white/90 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line w-5"></i>
                    <span class="font-medium">Tableau de bord</span>
                    @if(request()->routeIs('admin.dashboard'))
                        <i class="fas fa-chevron-right ml-auto text-green-300"></i>
                    @endif
                </a>

                <a href="{{ route('admin.vendors.index') }}" 
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl text-white/90 hover:text-white {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
                    <i class="fas fa-store w-5"></i>
                    <span class="font-medium">Vendeurs</span>
                    @if(request()->routeIs('admin.vendors.*'))
                        <i class="fas fa-chevron-right ml-auto text-green-300"></i>
                    @endif
                </a>

                <a href="{{ route('admin.clients.index') }}" 
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl text-white/90 hover:text-white {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span class="font-medium">Clients</span>
                    @if(request()->routeIs('admin.clients.*'))
                        <i class="fas fa-chevron-right ml-auto text-green-300"></i>
                    @endif
                </a>

                <a href="{{ route('admin.products.index') }}" 
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl text-white/90 hover:text-white {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box w-5"></i>
                    <span class="font-medium">Produits</span>
                    @if(request()->routeIs('admin.products.*'))
                        <i class="fas fa-chevron-right ml-auto text-green-300"></i>
                    @endif
                </a>

                <a href="{{ route('admin.categories.index') }}" 
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl text-white/90 hover:text-white {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags w-5"></i>
                    <span class="font-medium">Catégories</span>
                    @if(request()->routeIs('admin.categories.*'))
                        <i class="fas fa-chevron-right ml-auto text-green-300"></i>
                    @endif
                </a>

                <a href="{{ route('admin.orders.index') }}" 
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl text-white/90 hover:text-white {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart w-5"></i>
                    <span class="font-medium">Commandes</span>
                    @if(request()->routeIs('admin.orders.*'))
                        <i class="fas fa-chevron-right ml-auto text-green-300"></i>
                    @endif
                </a>

                <a href="{{ route('admin.logs.index') }}" 
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-xl text-white/90 hover:text-white {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                    <i class="fas fa-history w-5"></i>
                    <span class="font-medium">Logs</span>
                    @if(request()->routeIs('admin.logs.*'))
                        <i class="fas fa-chevron-right ml-auto text-green-300"></i>
                    @endif
                </a>
            </nav>

            <!-- Actions Footer -->
            <div class="space-y-3 mt-6">
                <!-- Retour au site -->
                <a href="{{ route('home') }}" 
                   class="flex items-center justify-center space-x-2 px-4 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-all">
                    <i class="fas fa-home"></i>
                    <span class="font-medium">Retour au site</span>
                </a>
                
                <!-- Déconnexion -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-red-500/20 hover:bg-red-500/30 text-red-100 rounded-xl transition-all">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="font-medium">Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Overlay mobile -->
    <div id="adminOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden"></div>

    <!-- Main Content -->
    <main class="flex-1 min-h-screen lg:ml-0">
        <!-- Top Bar -->
        <header class="bg-white/80 backdrop-blur-lg shadow-sm border-b border-gray-200">
            <div class="px-4 sm:px-6 lg:px-8 py-4 flex flex-wrap gap-4 justify-between items-center">
                <div class="flex items-center gap-3">
                    <button id="adminMenuButton" type="button" class="lg:hidden w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors" aria-label="Ouvrir le menu">
                        <i class="fas fa-bars text-gray-600"></i>
                    </button>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">@yield('title')</h1>
                        <p class="text-sm text-gray-500">@yield('subtitle', 'Administration GuinéeMall')</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    @php
                        $adminNotifications = auth()->user()?->notifications()->latest()->take(5)->get() ?? collect();
                        $adminUnreadCount = auth()->user()?->unreadNotifications()->count() ?? 0;
                    @endphp
                    <details class="relative">
                        <summary class="list-none w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors cursor-pointer">
                            <i class="fas fa-bell text-gray-600"></i>
                            @if($adminUnreadCount > 0)
                                <span class="absolute -top-1 -right-1 min-w-[16px] h-4 px-1 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center">
                                    {{ $adminUnreadCount }}
                                </span>
                            @endif
                        </summary>
                        <div class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-lg border border-gray-100 p-4 z-50">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-sm font-semibold text-gray-800">Notifications</p>
                                @if($adminUnreadCount > 0)
                                    <span class="text-xs font-semibold bg-green-100 text-green-700 px-2 py-1 rounded-full">
                                        {{ $adminUnreadCount }} non lue(s)
                                    </span>
                                @endif
                            </div>
                            @if($adminNotifications->isEmpty())
                                <p class="text-xs text-gray-500">Aucune notification.</p>
                            @else
                                <div class="space-y-3">
                                    @foreach($adminNotifications as $notification)
                                        @php $data = $notification->data ?? []; @endphp
                                        <div class="p-3 rounded-lg border border-gray-100 bg-gray-50">
                                            <p class="text-xs font-semibold text-gray-900">{{ $data['title'] ?? 'Notification' }}</p>
                                            <p class="text-xs text-gray-600 mt-1">{{ $data['message'] ?? '' }}</p>
                                            @if(!empty($data['action_url']))
                                                <a href="{{ $data['action_url'] }}" class="inline-flex items-center mt-2 text-[11px] font-semibold text-green-600 hover:text-green-700">
                                                    {{ $data['action_text'] ?? 'Voir' }}
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </details>
                    
                    <!-- User Menu -->
                    <div class="flex items-center space-x-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ now()->format('H:i') }}</p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="p-4 sm:p-6 lg:p-8 min-h-[calc(100vh-80px)]">
            @yield('content')
        </div>
    </main>

</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des sidebar items
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    sidebarItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(8px)';
        });
        item.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateX(0)';
            }
        });
    });

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Toggle sidebar mobile
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('adminOverlay');
    const menuButton = document.getElementById('adminMenuButton');

    const openSidebar = () => {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    const closeSidebar = () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    if (menuButton) {
        menuButton.addEventListener('click', openSidebar);
    }
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }
});
</script>

</body>
</html>
