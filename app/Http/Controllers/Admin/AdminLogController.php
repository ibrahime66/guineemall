<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use Illuminate\Http\Request;

class AdminLogController extends Controller
{
    /**
     * Liste des logs admin
     */
    public function index(Request $request)
    {
        $query = AdminLog::with('admin');

        // Filtrer par admin
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        // Filtrer par action
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        $logs = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.logs.index', compact('logs'));
    }

    /**
     * Détail d’un log
     */
    public function show(AdminLog $adminLog)
    {
        return view('admin.logs.show', compact('adminLog'));
    }

    /**
     * Supprimer tous les logs (danger contrôlé)
     */
    public function destroyAll()
    {
        AdminLog::truncate();

        return redirect()
            ->route('admin.logs.index')
            ->with('success', 'Tous les logs ont été supprimés');
    }
}
