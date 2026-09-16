<?php

namespace App\Traits;

use App\Models\Audit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function (Model $model) {
            self::enregistrerAudit($model, 'created');
        });

        static::updated(function (Model $model) {
            $dirty = $model->getDirty();
            unset($dirty['updated_at']);

            if (empty($dirty)) {
                return;
            }

            $avant = [];
            $apres = [];

            foreach ($dirty as $cle => $valeur) {
                $avant[$cle] = $model->getOriginal($cle);
                $apres[$cle] = $valeur;
            }

            self::enregistrerAudit($model, 'updated', [
                'avant' => $avant,
                'apres' => $apres,
            ]);
        });

        static::deleted(function (Model $model) {
            self::enregistrerAudit($model, 'deleted');
        });
    }

    protected static function enregistrerAudit(Model $model, string $action, array $details = []): void
    {
        try {
            Audit::create([
                'user_id'     => Auth::id(),
                'action'      => $action,
                'table_cible' => $model->getTable(),
                'id_cible'    => $model->id,
                'details'     => array_merge([
                    'label' => $model->nom
                        ?? $model->libelle
                        ?? $model->reference_unique
                        ?? $model->email
                        ?? "#{$model->id}",
                ], $details),
                'date_action' => now(),
                'ip'          => request()->ip(),
                'user_agent'  => substr(request()->userAgent() ?? '', 0, 255),
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur écriture audit', ['message' => $e->getMessage()]);
        }
    }
}