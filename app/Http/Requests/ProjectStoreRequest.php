<?php

namespace App\Http\Requests;

use App\Contracts\StatusEnum;
use App\Contracts\VisibilityEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:projects,name,' . $this->path(),
            'url' => 'nullable|string|url|max:255',
            'status' => ['required', Rule::enum(StatusEnum::class)],
            'visibility' => ['required', Rule::enum(VisibilityEnum::class)],
        ];
    }
}
