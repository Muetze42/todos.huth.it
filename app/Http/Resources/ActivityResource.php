<?php

namespace App\Http\Resources;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /* @var \App\Models\Activity $this */
        /* @var \App\Models\User $causer */
        $causer = $this->causer;
        $project = $this->findProject();
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

    protected function findProject(): Project
    {
        /* @var \App\Models\Activity $this */
        if ($this->subject instanceof Project) {
            return $this->subject;
        }

        return $this->subject->project;
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

        return 'Todo: Comments, Tasks etc';
    }
}
