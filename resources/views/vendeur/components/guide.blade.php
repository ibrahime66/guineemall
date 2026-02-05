<!-- MINI GUIDE VENDEUR INTÉGRÉ -->
<div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200 mb-8">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-3">
                <i class="fas fa-compass text-purple-600 text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Guide vendeur</h3>
                <p class="text-sm text-gray-600">Votre progression vers le succès</p>
            </div>
        </div>
        <div class="text-sm text-purple-600 font-medium">
            Étape {{ auth()->user()->vendor ? (auth()->user()->vendor->products->count() > 0 ? '3' : (auth()->user()->vendor->description ? '2' : '1')) : '0' }} / 5
        </div>
    </div>
    
    <!-- Barre de progression -->
    <div class="mb-6">
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-3 rounded-full transition-all duration-500" style="width: {{ auth()->user()->vendor ? (auth()->user()->vendor->products->count() > 0 ? '60' : (auth()->user()->vendor->description ? '40' : '20')) : '0' }}%"></div>
        </div>
    </div>
    
    <!-- Étapes -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <!-- Étape 1 -->
        <div class="text-center">
            <div class="w-12 h-12 {{ auth()->user()->vendor ? 'bg-green-100 border-green-300' : 'bg-gray-100 border-gray-300' }} rounded-full flex items-center justify-center mx-auto mb-2 border-2">
                @if(auth()->user()->vendor)
                    <i class="fas fa-check text-green-600"></i>
                @else
                    <span class="text-gray-400 font-bold">1</span>
                @endif
            </div>
            <p class="text-xs font-medium {{ auth()->user()->vendor ? 'text-green-600' : 'text-gray-500' }}">
                Créer boutique
            </p>
        </div>
        
        <!-- Étape 2 -->
        <div class="text-center">
            <div class="w-12 h-12 {{ auth()->user()->vendor && auth()->user()->vendor->description ? 'bg-green-100 border-green-300' : (auth()->user()->vendor ? 'bg-orange-100 border-orange-300' : 'bg-gray-100 border-gray-300') }} rounded-full flex items-center justify-center mx-auto mb-2 border-2">
                @if(auth()->user()->vendor && auth()->user()->vendor->description)
                    <i class="fas fa-check text-green-600"></i>
                @elseif(auth()->user()->vendor)
                    <span class="text-orange-600 font-bold">2</span>
                @else
                    <span class="text-gray-400 font-bold">2</span>
                @endif
            </div>
            <p class="text-xs font-medium {{ auth()->user()->vendor && auth()->user()->vendor->description ? 'text-green-600' : (auth()->user()->vendor ? 'text-orange-600' : 'text-gray-500') }}">
                Compléter infos
            </p>
        </div>
        
        <!-- Étape 3 -->
        <div class="text-center">
            <div class="w-12 h-12 {{ auth()->user()->vendor && auth()->user()->vendor->products->count() > 0 ? 'bg-green-100 border-green-300' : (auth()->user()->vendor ? 'bg-orange-100 border-orange-300' : 'bg-gray-100 border-gray-300') }} rounded-full flex items-center justify-center mx-auto mb-2 border-2">
                @if(auth()->user()->vendor && auth()->user()->vendor->products->count() > 0)
                    <i class="fas fa-check text-green-600"></i>
                @elseif(auth()->user()->vendor)
                    <span class="text-orange-600 font-bold">3</span>
                @else
                    <span class="text-gray-400 font-bold">3</span>
                @endif
            </div>
            <p class="text-xs font-medium {{ auth()->user()->vendor && auth()->user()->vendor->products->count() > 0 ? 'text-green-600' : (auth()->user()->vendor ? 'text-orange-600' : 'text-gray-500') }}">
                Ajouter produits
            </p>
        </div>
        
        <!-- Étape 4 -->
        <div class="text-center">
            <div class="w-12 h-12 bg-gray-100 border-gray-300 rounded-full flex items-center justify-center mx-auto mb-2 border-2">
                <span class="text-gray-400 font-bold">4</span>
            </div>
            <p class="text-xs font-medium text-gray-500">
                Activer produits
            </p>
        </div>
        
        <!-- Étape 5 -->
        <div class="text-center">
            <div class="w-12 h-12 bg-gray-100 border-gray-300 rounded-full flex items-center justify-center mx-auto mb-2 border-2">
                <span class="text-gray-400 font-bold">5</span>
            </div>
            <p class="text-xs font-medium text-gray-500">
                Première vente
            </p>
        </div>
    </div>
    
    <!-- Message motivant -->
    <div class="mt-4 text-center">
        <p class="text-sm font-medium text-gray-700">
            @if(auth()->user()->vendor && auth()->user()->vendor->products->count() > 0)
                🎉 Excellent ! Vous êtes à 2 étapes de votre première vente !
            @elseif(auth()->user()->vendor)
                💪 Continuez ! Ajoutez vos produits pour commencer à vendre
            @else
                🚀 Commencez par créer votre boutique pour vendre sur GuinéeMall
            @endif
        </p>
        
        @if(!auth()->user()->vendor)
        <a href="{{ route('vendeur.profile.create') }}" class="inline-flex items-center bg-purple-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-purple-700 transition-colors mt-2">
            <i class="fas fa-rocket mr-2"></i>
            Créer ma boutique
        </a>
        @elseif(auth()->user()->vendor && auth()->user()->vendor->products->count() == 0)
        <a href="{{ route('vendeur.products.create') }}" class="inline-flex items-center bg-purple-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-purple-700 transition-colors mt-2">
            <i class="fas fa-plus mr-2"></i>
            Ajouter mon premier produit
        </a>
        @endif
    </div>
</div>
