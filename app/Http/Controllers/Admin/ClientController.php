<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Liste des clients.
     */
    public function index(Request $request)
    {
        $query = Client::withCount('commandes')
            ->withSum('commandes', 'montant_total');

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtre type
        if ($request->filled('type')) {
            if ($request->type === 'guest') {
                $query->where('est_guest', true);
            } elseif ($request->type === 'compte') {
                $query->where('est_guest', false);
            }
        }

        // Tri
        $tri = $request->get('tri', 'recent');
        switch ($tri) {
            case 'nom':
                $query->orderBy('nom');
                break;
            case 'commandes':
                $query->orderByDesc('commandes_count');
                break;
            case 'ca':
                $query->orderByDesc('commandes_sum_montant_total');
                break;
            default:
                $query->orderByDesc('created_at');
        }

        $clients = $query->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total'    => Client::count(),
            'guests'   => Client::where('est_guest', true)->count(),
            'comptes'  => Client::where('est_guest', false)->count(),
            'nouveaux' => Client::whereDate('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('admin.clients.index', compact('clients', 'stats'));
    }

    /**
     * Détail d'un client.
     */
    public function show($id)
    {
        $client = Client::with([
            'adresses',
            'commandes' => function ($q) {
                $q->with('lignes')->orderByDesc('created_at');
            },
        ])->findOrFail($id);

        // Stats client
        $stats = [
            'commandes_total'    => $client->commandes->count(),
            'commandes_livrees'  => $client->commandes->where('statut', 'livree')->count(),
            'ca_total'           => $client->commandes->whereIn('statut', ['livree', 'payee'])->sum('montant_total'),
            'derniere_commande'  => $client->commandes->first()?->date_commande,
        ];

        return view('admin.clients.show', compact('client', 'stats'));
    }

    /**
     * Supprimer un client.
     */
    public function destroy($id)
    {
        $client = Client::withCount('commandes')->findOrFail($id);

        // Bloquer si le client a des commandes
        if ($client->commandes_count > 0) {
            return back()->with(
                'error',
                "Impossible de supprimer ce client : il a {$client->commandes_count} commande(s) enregistrée(s)."
            );
        }

        $client->delete();

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client supprimé.');
    }
}