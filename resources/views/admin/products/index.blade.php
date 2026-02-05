@extends('admin.layouts.app')

@section('title', 'Gestion des produits')

@section('content')
<p class="text-gray-600 mb-4">
    Liste globale de tous les produits publiés par les vendeurs.
</p>

<div class="bg-white rounded shadow overflow-hidden">
    @if($products->isEmpty())
        <div class="p-4 text-gray-600">
            Aucun produit disponible.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-full">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3">Produit</th>
                    <th class="p-3">Catégorie</th>
                    <th class="p-3">Vendeur</th>
                    <th class="p-3">Statut</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3 font-medium">{{ $product->name }}</td>
                        <td class="p-3">{{ $product->category->name ?? '—' }}</td>
                        <td class="p-3">{{ $product->vendor->shop_name ?? '—' }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-sm
                                {{ $product->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                                {{ $product->status === 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="p-3">
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 items-start sm:items-center">
                                <a href="{{ route('admin.products.show', $product) }}"
                                   class="text-blue-600 hover:underline">Voir</a>
                                @if($product->status === 'active')
                                    <form method="POST" action="{{ route('admin.products.deactivate', $product) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="text-red-600 hover:underline">Désactiver</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.products.activate', $product) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="text-green-600 hover:underline">Activer</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
