<?php

namespace App\Models;

use App\Contracts\Models\HasVisibilityTrait;
use App\Contracts\Models\LogsActivitiesTrait;
use App\Contracts\Models\StatusEnum;
use App\Contracts\Models\VisibilityEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Task extends Model implements Sortable
{
    use HasFactory;
    use LogsActivitiesTrait;
    use SoftDeletes;
    use SortableTrait;
    use HasVisibilityTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'visibility',
        'status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'visibility' => VisibilityEnum::class,
        'status' => StatusEnum::class,
    ];

    /**
     * Restrict the order calculations to fields value of the model instance.
     */
    public function buildSortQuery(): Builder
    {
        return static::query()->where('project_id', $this->project_id);
    }

    /**
     * Get the project that owns the task.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
