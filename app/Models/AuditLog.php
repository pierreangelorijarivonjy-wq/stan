<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    protected $fillable = [
        'event',
        'auditable_type',
        'auditable_id',
        'user_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur qui a effectué l'action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation polymorphique avec l'entité auditée
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Obtenir une description lisible de l'action
     */
    public function getReadableDescriptionAttribute(): string
    {
        if ($this->description) {
            return $this->description;
        }

        $userName = $this->user ? $this->user->name : 'Système';
        $modelName = class_basename($this->auditable_type);

        return match ($this->event) {
            'created' => "{$userName} a créé un(e) {$modelName}",
            'updated' => "{$userName} a modifié un(e) {$modelName}",
            'deleted' => "{$userName} a supprimé un(e) {$modelName}",
            'login' => "{$userName} s'est connecté",
            'logout' => "{$userName} s'est déconnecté",
            default => "{$userName} - {$this->event}",
        };
    }

    /**
     * Obtenir les changements sous forme lisible
     */
    public function getChangesAttribute(): array
    {
        if (!$this->old_values || !$this->new_values) {
            return [];
        }

        $changes = [];
        foreach ($this->new_values as $key => $newValue) {
            $oldValue = $this->old_values[$key] ?? null;

            if ($oldValue != $newValue) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }

    /**
     * Scope pour filtrer par type d'événement
     */
    public function scopeOfEvent($query, string $event)
    {
        return $query->where('event', $event);
    }

    /**
     * Scope pour filtrer par utilisateur
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour filtrer par type d'entité
     */
    public function scopeForModel($query, string $modelType)
    {
        return $query->where('auditable_type', $modelType);
    }

    /**
     * Scope pour les événements récents
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
