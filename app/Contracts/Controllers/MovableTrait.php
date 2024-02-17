<?php

namespace App\Contracts\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait MovableTrait
{
    protected function doMove(Request $request, Model $model): RedirectResponse
    {
        $this->authorize('update', $model);
        $request->validate(['method' => ['required', 'in:moveOrderDown,moveOrderUp,moveToStart,moveToEnd']]);
        $model->{$request->input('method')}();

        return redirect()->back();
    }
}
