<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuditController extends Controller
{
    private function checkSuperAdmin()
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Accès réservé aux super-administrateurs.');
        }
    }

    public function index(Request $request)
    {
        $this->checkSuperAdmin();

        $query = Audit::with('user')->orderByDesc('date_action');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('table_cible')) {
            $query->where('table_cible', $request->table_cible);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('details->label', 'like', "%{$search}%")
                    ->orWhere('ip', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_action', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_action', '<=', $request->date_fin);
        }

        $audits = $query->paginate(30)->withQueryString();

        $stats = [
            'total'         => Audit::count(),
            'aujourdhui'    => Audit::whereDate('date_action', today())->count(),
            'cette_semaine' => Audit::whereBetween('date_action', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'utilisateurs'  => Audit::distinct('user_id')->count('user_id'),
        ];

        $utilisateurs = User::orderBy('name')->get(['id', 'name', 'surname']);
        $actions = Audit::distinct()->pluck('action')->filter()->values();
        $tablesCibles = Audit::distinct()->pluck('table_cible')->filter()->values();

        return view('admin.audits.index', compact(
            'audits',
            'stats',
            'utilisateurs',
            'actions',
            'tablesCibles'
        ));
    }

    public function show($id)
    {
        $this->checkSuperAdmin();

        $audit = Audit::with('user')->findOrFail($id);

        return view('admin.audits.show', compact('audit'));
    }

    public function nettoyer()
    {
        $this->checkSuperAdmin();

        $count = Audit::where('date_action', '<', now()->subDays(90))->delete();

        return back()->with('success', "{$count} audit(s) de plus de 90 jours supprimés.");
    }
}