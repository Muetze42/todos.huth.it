<?php

namespace App\Http\Controllers;

use App\Contracts\StatusEnum;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string|int $id)
    {
        $request->validate([
            'status' => ['required', Rule::enum(StatusEnum::class)],
        ]);
        $model = is_numeric($id) ? Task::class : Project::class;
        $model = $model::findOrFail($id);
        $this->authorize('update', $model);

        $model->update(['status' => StatusEnum::from($request->input('status'))]);

        return redirect()->back();
    }
}
