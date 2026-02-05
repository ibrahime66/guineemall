@extends('admin.layouts.app')

@section('title', 'Tableau de bord Administrateur - GuinéeMall')

@section('styles')
<style>
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
    .card-hover {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-hover:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(16, 185, 129, 0.3);
    }
    .pulse-glow {
        animation: pulseGlow 2s ease-in-out infinite;
    }
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.5); }
        50% { box-shadow: 0 0 40px rgba(16, 185, 129, 0.8); }
    }
    .slide-in {
        animation: slideIn 0.6s ease-out;
    }
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    .counter {
        transition: all 0.3s ease;
    }
    .counter:hover {
        transform: scale(1.1);
    }
    
    /* Charts styling */
    .chart-container {
        position: relative;
        height: 300px;
    }
    
    /* Progress bars */
    .progress-bar {
        transition: width 1s ease-in-out;
    }
    
    /* Notification badge */
    .notification-badge {
        animation: bounce 2s infinite;
    }
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <!-- Header avec Welcome Message -->
    <div class="mb-8 slide-in">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-black text-gray-800 mb-2">
                        Bienvenue, <span class="admin-gradient-text">{{ auth()->user()->name }}</span>
                    </h1>
                    <p class="text-gray-600">Voici un aperçu de votre plateforme GuinéeMall</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">{{ now()->format('l d F Y') }}</p>
                    <p class="text-lg font-bold text-green-600">{{ now()->format('H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes et Notifications -->
    @if($pendingVendors > 0 || $lowStockProducts > 0 || $outOfStockProducts > 0)
    <div class="mb-8 slide-in">
        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-amber-600 text-xl mr-3 notification-badge"></i>
                <div>
                    <p class="font-bold text-amber-800">Alertes système</p>
                    <div class="text-sm text-amber-700">
                        @if($pendingVendors > 0)
                            <span class="inline-block mr-4">👤 {{ $pendingVendors }} vendeur(s) en attente d'approbation</span>
                        @endif
                        @if($lowStockProducts > 0)
                            <span class="inline-block mr-4">📦 {{ $lowStockProducts }} produit(s) en stock faible</span>
                        @endif
                        @if($outOfStockProducts > 0)
                            <span class="inline-block">❌ {{ $outOfStockProducts }} produit(s) en rupture</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Notifications -->
    <div class="mb-8 slide-in">
        <x-notification-panel title="Notifications administrateur" />
    </div>

    <!-- KPIs Principaux -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Clients -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                @if($clientsGrowth > 0)
                    <span class="text-green-600 text-sm font-bold">
                        <i class="fas fa-arrow-up mr-1"></i>{{ number_format($clientsGrowth, 1) }}%
                    </span>
                @else
                    <span class="text-red-600 text-sm font-bold">
                        <i class="fas fa-arrow-down mr-1"></i>{{ number_format(abs($clientsGrowth), 1) }}%
                    </span>
                @endif
            </div>
            <p class="text-gray-500 text-sm mb-1">Clients</p>
            <p class="text-3xl font-black text-gray-800 counter">{{ number_format($clientsCount) }}</p>
            <p class="text-xs text-gray-400 mt-2">Total inscrits</p>
        </div>

        <!-- Vendeurs -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-store text-green-600 text-xl"></i>
                </div>
                @if($pendingVendors > 0)
                    <span class="bg-amber-100 text-amber-800 text-xs px-2 py-1 rounded-full font-bold">
                        {{ $pendingVendors }} en attente
                    </span>
                @endif
            </div>
            <p class="text-gray-500 text-sm mb-1">Vendeurs</p>
            <p class="text-3xl font-black text-gray-800 counter">{{ number_format($vendeursCount) }}</p>
            <p class="text-xs text-gray-400 mt-2">Partenaires actifs</p>
        </div>

        <!-- Produits -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-box text-purple-600 text-xl"></i>
                </div>
                @if($outOfStockProducts > 0)
                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full font-bold">
                        {{ $outOfStockProducts }} rupture
                    </span>
                @endif
            </div>
            <p class="text-gray-500 text-sm mb-1">Produits</p>
            <p class="text-3xl font-black text-gray-800 counter">{{ number_format($productsCount) }}</p>
            <p class="text-xs text-gray-400 mt-2">{{ $categoriesCount }} catégories</p>
        </div>

        <!-- Commandes -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-orange-600 text-xl"></i>
                </div>
                @if($ordersGrowth > 0)
                    <span class="text-green-600 text-sm font-bold">
                        <i class="fas fa-arrow-up mr-1"></i>{{ number_format($ordersGrowth, 1) }}%
                    </span>
                @else
                    <span class="text-red-600 text-sm font-bold">
                        <i class="fas fa-arrow-down mr-1"></i>{{ number_format(abs($ordersGrowth), 1) }}%
                    </span>
                @endif
            </div>
            <p class="text-gray-500 text-sm mb-1">Commandes</p>
            <p class="text-3xl font-black text-gray-800 counter">{{ number_format($ordersCount) }}</p>
            <p class="text-xs text-gray-400 mt-2">{{ $ordersPending }} en attente</p>
        </div>

        <!-- Chiffre d'Affaires -->
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
            </div>
            <p class="text-green-100 text-sm mb-1">Chiffre d'Affaires</p>
            <p class="text-3xl font-black counter">{{ number_format($chiffreAffaires, 0, ',', ' ') }}</p>
            <p class="text-xs text-green-100 mt-2">GNF • {{ number_format($monthlyRevenue, 0, ',', ' ') }} ce mois</p>
        </div>
    </div>

    <!-- Graphiques et Statistiques -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Graphique d'activité -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Activité des 7 derniers jours</h3>
                <div class="flex space-x-4 text-sm">
                    <span class="flex items-center"><span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>Commandes</span>
                    <span class="flex items-center"><span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>Utilisateurs</span>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <!-- Statistiques des commandes -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Répartition Commandes</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">En attente</span>
                        <span class="text-sm font-bold text-gray-800">{{ $ordersPending }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full progress-bar" style="width: {{ $ordersCount > 0 ? ($ordersPending / $ordersCount) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">En préparation</span>
                        <span class="text-sm font-bold text-gray-800">{{ $ordersPreparing }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full progress-bar" style="width: {{ $ordersCount > 0 ? ($ordersPreparing / $ordersCount) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Livrées</span>
                        <span class="text-sm font-bold text-green-600">{{ $ordersDelivered }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full progress-bar" style="width: {{ $ordersCount > 0 ? ($ordersDelivered / $ordersCount) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Annulées</span>
                        <span class="text-sm font-bold text-red-600">{{ $ordersCancelled }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full progress-bar" style="width: {{ $ordersCount > 0 ? ($ordersCancelled / $ordersCount) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableaux d'activité récente -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Commandes récentes -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Commandes récentes</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-green-600 hover:text-green-700 text-sm font-bold">Voir tout</a>
            </div>
            <div class="space-y-3">
                @forelse($recentOrders as $order)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-shopping-bag text-green-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">#{{ $order->id }}</p>
                                <p class="text-xs text-gray-500">{{ $order->user->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-800">{{ number_format($order->total_amount, 0, ',', ' ') }} GNF</p>
                            <span class="text-xs px-2 py-1 rounded-full 
                                @if($order->status == 'delivered') bg-green-100 text-green-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">Aucune commande récente</p>
                @endforelse
            </div>
        </div>

        <!-- Utilisateurs récents -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Nouveaux utilisateurs</h3>
                <a href="{{ route('admin.clients.index') }}" class="text-green-600 hover:text-green-700 text-sm font-bold">Voir tout</a>
            </div>
            <div class="space-y-3">
                @forelse($recentUsers as $user)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs px-2 py-1 rounded-full 
                                @if($user->role == 'client') bg-purple-100 text-purple-800
                                @elseif($user->role == 'vendeur') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $user->role }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">Aucun nouvel utilisateur</p>
                @endforelse
            </div>
        </div>

        <!-- Produits populaires -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Produits populaires</h3>
                <a href="{{ route('admin.products.index') }}" class="text-green-600 hover:text-green-700 text-sm font-bold">Voir tout</a>
            </div>
            <div class="space-y-3">
                @forelse($topProducts as $product)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-box text-purple-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800 truncate max-w-xs">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500">{{ $product->vendor->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-800">{{ $product->order_items_count ?? 0 }}</p>
                            <p class="text-xs text-gray-500">ventes</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">Aucun produit populaire</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des compteurs
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/[^0-9]/g, ''));
        let current = 0;
        const increment = target / 100;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            counter.textContent = Math.floor(current).toLocaleString();
        }, 20);
    });

    // Graphique d'activité
    const ctx = document.getElementById('activityChart').getContext('2d');
    const dailyStats = @json($dailyStats);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dailyStats.map(stat => stat.date),
            datasets: [{
                label: 'Commandes',
                data: dailyStats.map(stat => stat.orders),
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Utilisateurs',
                data: dailyStats.map(stat => stat.users),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Animation des progress bars
    setTimeout(() => {
        document.querySelectorAll('.progress-bar').forEach(bar => {
            bar.style.width = bar.style.width;
        });
    }, 500);
});
</script>
@endsection
