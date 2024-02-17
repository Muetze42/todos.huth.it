<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use App\Models\Task;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Task $task)
    {
        if (!$request->user()->is_admin) {
            abort(403);
        }

        $request->validate([
            'url' => ['required', 'url'],
        ]);

        $task->references()->create($request->only('url'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Reference $reference)
    {
        if (!$request->user()->is_admin) {
            abort(403);
        }

        $reference->delete();

        return redirect()->back();
    }
}
