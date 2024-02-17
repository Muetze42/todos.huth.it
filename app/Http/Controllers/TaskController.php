<?php

namespace App\Http\Controllers;

use App\Contracts\Controllers\MovableTrait;
use App\Contracts\Models\StatusEnum;
use App\Contracts\Models\VisibilityEnum;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    use MovableTrait;

    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1500',
            'status' => ['required', Rule::enum(StatusEnum::class)],
        ]);

        $project->tasks()->create(array_merge($validated, ['visibility' => VisibilityEnum::GUEST]));

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1500',
        ]);

        $task->update($validated);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->back();
    }

    public function move(Request $request, Task $task)
    {
        return $this->doMove($request, $task);
    }
}
