@extends('admin.layouts.app')

@section('title', 'Gestion des vendeurs')

@section('content')

<div class="bg-white shadow rounded overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Boutique</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Statut</th>
                <th class="p-3 text-left">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($vendors as $vendor)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3 font-medium">
                        {{ $vendor->shop_name }}
                    </td>

                    <td class="p-3">
                        {{ $vendor->user->email }}
                    </td>

                    <td class="p-3">
                        <span class="px-3 py-1 rounded text-sm font-semibold
                            @if($vendor->status === 'approved')
                                bg-green-100 text-green-800
                            @elseif($vendor->status === 'suspended')
                                bg-red-100 text-red-800
                            @else
                                bg-yellow-100 text-yellow-800
                            @endif
                        ">
                            {{ ucfirst($vendor->status) }}
                        </span>
                    </td>

                    <td class="p-3">
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 items-start sm:items-center">

                        <!-- Voir -->
                            <a href="{{ route('admin.vendors.show', $vendor) }}"
                               class="text-blue-600 hover:underline">
                                Voir
                            </a>

                        <!-- Approuver -->
                            @if($vendor->status !== 'approved')
                                <form method="POST"
                                      action="{{ route('admin.vendors.approve', $vendor) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button class="text-green-600 hover:underline">
                                        Approuver
                                    </button>
                                </form>
                            @endif

                        <!-- Suspendre -->
                            @if($vendor->status === 'approved')
                                <form method="POST"
                                      action="{{ route('admin.vendors.suspend', $vendor) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button class="text-red-600 hover:underline">
                                        Suspendre
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-6 text-center text-gray-500">
                        Aucun vendeur trouvé
                    </td>
                </tr>
            @endforelse
        </tbody>
        </table>
    </div>

</div>

@endsection
