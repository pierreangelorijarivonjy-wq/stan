<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    /**
     * Boot le trait Auditable
     */
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            $model->auditEvent('created');
        });

        static::updated(function ($model) {
            if ($model->wasChanged()) {
                $model->auditEvent('updated');
            }
        });

        static::deleted(function ($model) {
            $model->auditEvent('deleted');
        });
    }

    /**
     * Enregistrer un événement d'audit
     */
    protected function auditEvent(string $event, string $description = null): void
    {
        $oldValues = null;
        $newValues = $this->getAttributes();

        if ($event === 'updated') {
            $oldValues = $this->getOriginal();
            // Ne garder que les champs qui ont changé
            $changedFields = array_keys($this->getChanges());
            $oldValues = array_intersect_key($oldValues, array_flip($changedFields));
            $newValues = array_intersect_key($newValues, array_flip($changedFields));
        }

        AuditLog::create([
            'event' => $event,
            'auditable_type' => get_class($this),
            'auditable_id' => $this->id,
            'user_id' => Auth::id(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description,
        ]);
    }

    /**
     * Enregistrer un événement personnalisé
     */
    public function audit(string $event, string $description = null, array $data = []): void
    {
        AuditLog::create([
            'event' => $event,
            'auditable_type' => get_class($this),
            'auditable_id' => $this->id,
            'user_id' => Auth::id(),
            'new_values' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description,
        ]);
    }

    /**
     * Relation avec les logs d'audit
     */
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable')->latest();
    }

    /**
     * Obtenir l'historique d'audit
     */
    public function getAuditHistory()
    {
        return $this->auditLogs()->with('user')->get();
    }
}
