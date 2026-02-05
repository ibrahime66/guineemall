<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Notifications\VendorApproved;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VendorController extends Controller
{
    /**
     * Liste des vendeurs (admin)
     * - pagination
     * - filtre par statut
     */
    public function index(Request $request): View
    {
        $query = Vendor::with('user');

        // Filtrer par statut (pending / approved / suspended)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $vendors = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * Détails d’un vendeur (admin)
     */
    public function show(Vendor $vendor): View
    {
        $vendor->load(['user', 'products']);

        return view('admin.vendors.show', compact('vendor'));
    }

    /**
     * Approuver un vendeur
     */
    public function approve(Vendor $vendor): RedirectResponse
    {
        $vendor->update([
            'status' => 'approved',
        ]);

        // Sécurité : activer aussi l'utilisateur
        $vendor->user->update([
            'is_active' => true,
        ]);

        if ($vendor->user) {
            $vendor->user->notify(new VendorApproved($vendor));
        }

        return redirect()
            ->route('admin.vendors.index')
            ->with('success', 'Vendeur approuvé avec succès.');
    }

    /**
     * Suspendre un vendeur
     */
    public function suspend(Vendor $vendor): RedirectResponse
    {
        $vendor->update([
            'status' => 'suspended',
        ]);

        // Sécurité : bloquer l'utilisateur
        $vendor->user->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route('admin.vendors.index')
            ->with('success', 'Vendeur suspendu.');
    }

    /**
     * Réactiver un vendeur suspendu
     */
    public function reactivate(Vendor $vendor): RedirectResponse
    {
        $vendor->update([
            'status' => 'approved',
        ]);

        $vendor->user->update([
            'is_active' => true,
        ]);

        if ($vendor->user) {
            $vendor->user->notify(new VendorApproved($vendor));
        }

        return redirect()
            ->route('admin.vendors.index')
            ->with('success', 'Vendeur réactivé.');
    }
}
