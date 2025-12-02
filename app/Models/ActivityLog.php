<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'description',
        'metadata',
    ];

    protected $appends = [
        'user_name',
        'auditable_type_name',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    /**
     * Get the user that owns the activity log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUserNameAttribute(): ?string
    {
        return $this->user ? $this->user->name : 'System';
    }

    /**
     * Get the parent auditable model (morph to various models).
     */
    public function auditable()
    {
        return $this->morphTo();
    }


    /**
     * Log an activity
     * @param int|null $userId
     * @param string $action
     * @param Model $activity
     * @param string $message
     * @param array $metadata
     * @param array|null $oldValues
     * @param array|null $newValues
     */
    public static function logActivity($userId, $action, Model $activity, $message, $metadata = [], $oldValues = null, $newValues = null)
    {
        $userName = User::find($userId)?->name ?? 'System';
        $metadata['old_values'] = $oldValues;
        $metadata['new_values'] = $newValues;

        $description = "{$userName} {$message}";

        self::create([
            'user_id' => $userId,
            'action' => $action,
            'auditable_type' => get_class($activity),
            'auditable_id' => $activity->id,
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }

    public function getAuditableTypeNameAttribute(): string
    {
        $words = explode('\\', $this->auditable_type);
        return ucfirst(strtolower(end($words)));
    }
}
