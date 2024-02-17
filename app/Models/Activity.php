<?php

namespace App\Models;

use App\Contracts\Models\VisibilityEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity as Model;

class Activity extends Model
{
    use SoftDeletes;

    /**
     * The name of the "updated at" column.
     */
    public const UPDATED_AT = null;

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'properties' => 'collection',
        'visibility' => VisibilityEnum::class,
        'is_trashed' => 'bool',
    ];

    /**
     * Get the parent causer model (user).
     */
    public function causer(): MorphTo
    {
        return $this->morphTo()->withDefault([
            'name' => 'Unknown',
            'nickname' => 'Unknown',
            'avatar' => 'https://avatars.githubusercontent.com/u/0?v=4',
            'blog' => null,
        ]);
    }

    /**
     * Perform any actions required after the model boots.
     */
    public static function booted(): void
    {
        static::creating(function (self $activity) {
            $subject = $activity->subject;
            $activity->project_id = match (true) {
                $subject instanceof Project => $subject->getKey(),
                $subject instanceof Task => $subject->project_id,
                default => null
            };
            $activity->visibility = self::getVisibility($activity);
        });
        static::created(function (self $activity) {
            if ($activity->description == 'updated' && $activity->subject instanceof Project) {
                $properties = $activity->properties->toArray();
                $diff = array_diff($properties['attributes'], $properties['old']);

                if (!empty($diff['visibility'])) {
                    self::where('project_id', $activity->project_id)
                        ->where('id', '!=', $activity->id)
                        ->update(['visibility' => $activity->visibility]);
                }
            }
            if (in_array($activity->description, ['deleted', 'restored'])) {
                self::where('project_id', $activity->project_id)
                    ->update(['is_trashed' => $activity->description == 'restored']);
            }
        });
    }

    public static function getVisibility(self $activity)
    {
        $subject = $activity->subject;

        return match (true) {
            $subject instanceof Project => $subject->visibility,
            default => VisibilityEnum::ADMIN
        };
    }

    /**
     * Scope a query to only include popular users.
     */
    public function scopeAuthorized(Builder $query, ?User $user): void
    {
        if (!$user) {
            $query->where('visibility', VisibilityEnum::USER->value)
                ->where('is_trashed', false);
        } elseif (!$user->is_admin) {
            $query->where('visibility', '<', VisibilityEnum::ADMIN->value)
                ->where('is_trashed', false);
        } else {
            $query->where('is_trashed', false);
        }
    }
}
