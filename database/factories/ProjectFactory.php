<?php

namespace Database\Factories;

use App\Contracts\StatusEnum;
use App\Contracts\VisibilityEnum;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'visibility' => Arr::random(VisibilityEnum::cases()),
            'status' => Arr::random(StatusEnum::cases()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
