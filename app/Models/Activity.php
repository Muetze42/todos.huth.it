<?php

namespace App\Models;

use App\Contracts\FindProjectTrait;
use App\Contracts\Models\HasVisibilityTrait;
use App\Contracts\VisibilityEnum;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity as Model;

class Activity extends Model
{
    use SoftDeletes;
    use HasVisibilityTrait;
    use FindProjectTrait;

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
            $project = static::findProject($activity);
            $activity->project_id = $project->id;
            $activity->visibility = $project->visibility;
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
}
