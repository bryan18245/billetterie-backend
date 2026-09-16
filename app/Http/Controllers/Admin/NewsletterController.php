<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterAbonne;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    /**
     * Liste des abonnés.
     */
    public function index(Request $request)
    {
        $query = NewsletterAbonne::query();

        // Recherche
        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        // Filtre statut
        if ($request->filled('actif')) {
            $query->where('actif', $request->actif === '1');
        }

        $abonnes = $query->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        // Stats
        $stats = [
            'total'    => NewsletterAbonne::count(),
            'actifs'   => NewsletterAbonne::where('actif', true)->count(),
            'inactifs' => NewsletterAbonne::where('actif', false)->count(),
            'ce_mois'  => NewsletterAbonne::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('admin.newsletter.index', compact('abonnes', 'stats'));
    }

    /**
     * Activer / désactiver un abonné.
     */
    public function toggleActif($id)
    {
        $abonne = NewsletterAbonne::findOrFail($id);
        $abonne->update(['actif' => !$abonne->actif]);

        return back()->with(
            'success',
            $abonne->actif ? 'Abonné activé.' : 'Abonné désactivé.'
        );
    }

    /**
     * Supprimer un abonné.
     */
    public function destroy($id)
    {
        $abonne = NewsletterAbonne::findOrFail($id);
        $abonne->delete();

        return back()->with('success', 'Abonné supprimé.');
    }

    /**
     * Exporter en CSV.
     */
    public function export(): StreamedResponse
    {
        $filename = 'newsletter-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 pour Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // En-têtes
            fputcsv($handle, ['Email', 'Statut', 'Inscrit le'], ';');

            // Lignes
            NewsletterAbonne::orderByDesc('created_at')
                ->chunk(500, function ($abonnes) use ($handle) {
                    foreach ($abonnes as $abonne) {
                        fputcsv($handle, [
                            $abonne->email,
                            $abonne->actif ? 'Actif' : 'Inactif',
                            $abonne->created_at->format('d/m/Y H:i'),
                        ], ';');
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
    public function envoyer(Request $request)
    {
        $request->validate([
            'sujet'   => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $abonnes = NewsletterAbonne::where('actif', true)->get();

        // TODO : brancher un service d'email (Mailjet, SendGrid, SMTP)
        // foreach ($abonnes as $abonne) {
        //     Mail::to($abonne->email)->queue(new NewsletterMail($request->sujet, $request->message));
        // }

        Log::info("📧 Newsletter envoyée à {$abonnes->count()} abonnés", [
            'sujet'   => $request->sujet,
            'message' => $request->message,
        ]);

        return back()->with('success', "Newsletter en préparation pour {$abonnes->count()} abonné(s).");
    }
}