<?php

namespace App\Models;

use App\Contracts\Models\LogsActivitiesTrait;
use App\Http\Clients\GitHubBot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reference extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivitiesTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'url',
    ];

    /**
     * Get the task that owns the reference.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Perform any actions required after the model boots.
     */
    public static function booted(): void
    {
        static::created(function (Reference $reference): void {
            (new GitHubBot())->createAnIssueCommentByGitHubIssueUrl(
                message: trim(view('markdown.reference', [
                    'user' => '@' . $reference->activities()->first()->causer->nickname,
                    'project' => $reference->task->project->name,
                    'route' => route('projects.show', $reference->task->project),
                ])->render()),
                url: $reference->url
            );
        });
    }
}
