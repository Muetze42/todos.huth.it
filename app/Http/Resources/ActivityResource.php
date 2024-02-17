<?php

namespace App\Http\Resources;

use App\Contracts\FindProjectTrait;
use App\Models\Project;
use App\Models\Reference;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    use FindProjectTrait;

    /**
     * Transform the resource into an array.
     *
     * @throws \Exception
     */
    public function toArray(Request $request): array
    {
        /* @var \App\Models\Activity $this */
        /* @var \App\Models\User $causer */
        $causer = $this->causer;
        $project = static::findProject($this);
        $url = route('projects.show', $project->id);

        return [
            'id' => $this->id,
            'causer' => [
                'id' => $causer->getKey(),
                'name' => $causer->nickname,
                'avatar' => $causer->avatar,
            ],
            'route' => $url,
            'message' => $causer->nickname . $this->getMessage($project),
            'time' => $this->created_at->diffForHumans(),
        ];
    }

    protected function getMessage(Project $project): string
    {
        /* @var \App\Models\Activity $this */
        if ($this->subject instanceof Project) {
            return sprintf(
                ' %s the project „%s“',
                $this->description,
                $project->name
            );
        }
        if ($this->subject instanceof Task) {
            return sprintf(
                ' %s a Task for „%s“',
                $this->description,
                $project->name
            );
        }

        if ($this->subject instanceof Reference) {
            return sprintf(
                ' %s a Reference to „%s“',
                $this->description,
                $project->name
            );
        }

        return 'UNKNOWN';
    }
}
