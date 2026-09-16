<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ─── Stats principales ───
        $stats = [
            'commandes_total'   => Commande::count(),
            'commandes_jour'    => Commande::whereDate('created_at', today())->count(),
            'commandes_attente' => Commande::where('statut', 'en_attente')->count(),
            'ca_total'          => Commande::whereIn('statut', ['livree', 'payee'])->sum('montant_total'),
            'ca_jour'           => Commande::whereDate('created_at', today())->sum('montant_total'),
            'ca_mois'           => Commande::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->whereIn('statut', ['livree', 'payee'])
                ->sum('montant_total'),
            'produits_total'    => Produit::count(),
            'produits_actifs'   => Produit::where('actif', true)->count(),
            'clients_total'     => Client::count(),
        ];

        // ─── Graphique CA 30 derniers jours ───
        $ventesParJour = Commande::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(montant_total) as total'),
            DB::raw('COUNT(*) as nombre')
        )
            ->whereBetween('created_at', [now()->subDays(29), now()])
            ->whereIn('statut', ['livree', 'payee'])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Créer un tableau continu de 30 jours
        $chartData = [
            'labels'  => [],
            'totaux'  => [],
            'nombres' => [],
        ];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartData['labels'][]  = now()->subDays($i)->format('d/m');
            $chartData['totaux'][]  = (float) ($ventesParJour[$date]->total ?? 0);
            $chartData['nombres'][] = (int) ($ventesParJour[$date]->nombre ?? 0);
        }

        // ─── Top 5 produits vendus ───
        $topProduits = DB::table('lignes_commandes')
            ->select(
                'produit_id',
                'nom_produit',
                DB::raw('SUM(quantite) as total_vendus'),
                DB::raw('SUM(sous_total) as ca_genere')
            )
            ->whereNotNull('produit_id')
            ->groupBy('produit_id', 'nom_produit')
            ->orderByDesc('total_vendus')
            ->limit(5)
            ->get();

        // Charger les images des produits
        $topProduits = $topProduits->map(function ($item) {
            $produit = Produit::with('imagePrincipale')->find($item->produit_id);
            $item->image = $produit?->imagePrincipale?->chemin;
            return $item;
        });

        // ─── Top 5 clients ───
        $topClients = Client::withSum(['commandes as ca_total' => function ($q) {
            $q->whereIn('statut', ['livree', 'payee']);
        }], 'montant_total')
            ->withCount('commandes')
            ->having('ca_total', '>', 0)
            ->orderByDesc('ca_total')
            ->limit(5)
            ->get();

        // ─── Commandes en attente ───
        $commandesEnAttente = Commande::with('client')
            ->where('statut', 'en_attente')
            ->orderBy('created_at')
            ->limit(5)
            ->get();

        // ─── Commandes non payées depuis 3 jours ───
        $commandesNonPayees = Commande::with('client')
            ->where('statut_paiement', 'en_attente')
            ->where('created_at', '<', now()->subDays(3))
            ->orderBy('created_at')
            ->limit(5)
            ->get();

        // ─── Produits stock faible ───
        $produitsRupture = Produit::with('categorie')
            ->where('stock', '<=', 5)
            ->where('actif', true)
            ->orderBy('stock')
            ->limit(10)
            ->get();

        // ─── Dernières commandes ───
        $dernieresCommandes = Commande::with('client')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'chartData',
            'topProduits',
            'topClients',
            'commandesEnAttente',
            'commandesNonPayees',
            'produitsRupture',
            'dernieresCommandes'
        ));
    }
}