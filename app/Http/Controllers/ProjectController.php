<?php

namespace App\Http\Controllers;

use App\Contracts\Controllers\MovableTrait;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\ProjectResource;
use App\Models\Activity;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Inertia\Inertia;

class ProjectController extends Controller
{
    use MovableTrait;

    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    /**1
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Project/Index', [
            'projects' => ProjectResource::collection(
                Project::authorized($request->user())
                    ->orderBy('status')
                    ->ordered()
                    ->paginate(10)
            ),
            'activities' => ActivityResource::collection(
                Activity::authorized($request->user())
                    ->has('subject')
                    ->orderByDesc('created_at')
                    ->limit(5)
                    ->get()
            ),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectStoreRequest $request)
    {
        $project = Project::create($request->validated());

        return redirect()->route('project.show', $project);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Project $project)
    {
        Inertia::share('page.title', $project->name);
        /* @var \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\Task $tasks */
        $tasks = $project->tasks();

        return Inertia::render('Project/Show', [
            'project' => new ProjectResource($project),
            'tasks' => JsonResource::collection(
                $tasks->authorized($request->user())
                    ->ordered()
                    ->orderBy('status')
                    ->get()
            ),
            'activities' => ActivityResource::collection(
                Activity::whereProjectId($project->id)
                    ->authorized($request->user())
                    ->has('subject')
                    ->orderByDesc('created_at')
                    ->limit(5)
                    ->get()
            ),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectStoreRequest $request, Project $project)
    {
        $validated = $request->validated();
        $project->update($validated);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        // Todo: Implement destroy()
    }

    public function move(Request $request, Project $project)
    {
        return $this->doMove($request, $project);
    }
}
