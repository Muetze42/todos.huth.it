<?php

namespace App\Models;

use App\Contracts\Models\HasVisibilityTrait;
use App\Contracts\Models\LogsActivitiesTrait;
use App\Contracts\StatusEnum;
use App\Contracts\VisibilityEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Project extends Model implements Sortable
{
    use HasFactory;
    use HasUuids;
    use LogsActivitiesTrait;
    use SoftDeletes;
    use SortableTrait;
    use HasVisibilityTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'url',
        'status',
        'visibility',
    ];

    protected $guarded = [];

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
        return $this->status ? static::query()->where('status', $this->status) : static::query();
    }

    /**
     * Get the tasks for the project.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
