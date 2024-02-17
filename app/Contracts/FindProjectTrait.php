<?php

namespace App\Contracts;

use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use App\Models\Project;
use App\Models\Reference;
use App\Models\Task;
use Exception;

trait FindProjectTrait
{
    /**
     * @throws \Exception
     */
    protected static function findProject(Activity|ActivityResource $activity): Project
    {
        if ($activity->subject instanceof Task) {
            return $activity->subject->project;
        }
        if ($activity->subject instanceof Reference) {
            return $activity->subject->task->project;
        }
        if ($activity->subject instanceof Project) {
            return $activity->subject;
        }

        throw new Exception('Can not determine Project');
    }
}
