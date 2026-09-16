<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Paiement;
use App\Services\SasPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class PaiementController extends Controller
{
    /**
     * Initialiser un paiement SasPay.
     */
    public function initierSasPay(
        Request $request,
        SasPayService $sasPay
    ) {
        $validated = $request->validate([
            'commande_id' => [
                'required',
                'integer',
                'exists:commandes,id',
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
            ],

            'network' => [
                'required',
                'string',
                'in:orange_ci,mtn_ci,moov_ci,wave_ci',
            ],

            'first_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'last_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],
        ]);

        try {
            $commande = Commande::findOrFail(
                $validated['commande_id']
            );

            /*
             * IMPORTANT :
             * Le montant vient de la commande en base.
             * On ne fait PAS confiance à un montant envoyé
             * par Flutter/frontend.
             */
            $montant = $commande->montant;

            /*
             * Vérifier qu'un paiement n'est pas déjà en cours
             * ou déjà réussi pour cette commande.
             */
            $paiementExistant = Paiement::where(
                'commande_id',
                $commande->id
            )
                ->whereIn('statut', [
                    'PENDING',
                    'SUCCESS',
                ])
                ->latest()
                ->first();

            if ($paiementExistant) {
                return response()->json([
                    'success' => true,
                    'message' => 'Un paiement existe déjà pour cette commande.',
                    'paiement' => $paiementExistant,
                ]);
            }

            /*
             * Une clé unique pour cette intention de paiement.
             *
             * Elle doit être réutilisée si on retry
             * exactement la même tentative.
             */
            $idempotencyKey = (string) Str::uuid();

            $result = $sasPay->createPayment(
                amount: $montant,
                phone: $validated['phone'],
                network: $validated['network'],
                description: 'Paiement commande #' . $commande->id,
                customer: [
                    'email' => $validated['email'] ?? null,
                    'first_name' => $validated['first_name'] ?? '',
                    'last_name' => $validated['last_name'] ?? '',
                ],
                idempotencyKey: $idempotencyKey
            );

            /*
             * SasPay retourne un ID unique.
             */
            $sasPayPaymentId = $result['id'] ?? null;

            if (!$sasPayPaymentId) {
                throw new \RuntimeException(
                    'SasPay n\'a pas retourné d\'identifiant de paiement.'
                );
            }

            $paiement = Paiement::create([
                'commande_id' => $commande->id,
                'moyen' => $validated['network'],
                'reference' => $sasPayPaymentId,
                'montant' => $montant,
                'statut' => $result['status'] ?? 'PENDING',
                'date_paiement' => null,
            ]);

            /*
             * checkout_url peut être rempli.
             *
             * Dans ce cas, le client doit être redirigé
             * vers cette URL.
             */
            return response()->json([
                'success' => true,

                'message' => $result['message']
                    ?? 'Paiement initié.',

                'paiement' => [
                    'id' => $paiement->id,
                    'commande_id' => $paiement->commande_id,
                    'reference' => $paiement->reference,
                    'montant' => $paiement->montant,
                    'statut' => $paiement->statut,
                    'moyen' => $paiement->moyen,
                ],

                'saspay' => [
                    'id' => $sasPayPaymentId,
                    'status' => $result['status'] ?? null,
                    'checkout_url' => $result['checkout_url'] ?? null,
                ],
            ], 201);
        } catch (Throwable $e) {

            Log::error(
                'Erreur initialisation paiement SasPay',
                [
                    'commande_id' => $validated['commande_id'] ?? null,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Impossible d\'initialiser le paiement.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Vérifier manuellement le statut d'un paiement.
     */
    public function verifierSasPay(
        int $paiementId,
        SasPayService $sasPay
    ) {
        try {

            $paiement = Paiement::findOrFail($paiementId);

            if (!$paiement->reference) {
                return response()->json([
                    'success' => false,
                    'message' => 'Référence SasPay absente.',
                ], 422);
            }

            $result = $sasPay->verifyPayment(
                $paiement->reference
            );

            $status = $result['status'] ?? null;

            /*
             * Mettre à jour uniquement selon le statut
             * réellement retourné par SasPay.
             */
            if ($status === 'SUCCESS') {

                $paiement->update([
                    'statut' => 'SUCCESS',
                    'date_paiement' => now(),
                ]);
            } elseif ($status === 'FAILED') {

                $paiement->update([
                    'statut' => 'FAILED',
                ]);
            }

            return response()->json([
                'success' => true,
                'statut' => $paiement->statut,
                'saspay' => $result,
            ]);
        } catch (Throwable $e) {

            Log::error(
                'Erreur vérification paiement SasPay',
                [
                    'paiement_id' => $paiementId,
                    'error' => $e->getMessage(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification du paiement.',
            ], 500);
        }
    }
}