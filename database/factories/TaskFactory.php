<?php

namespace Database\Factories;

use App\Contracts\Models\StatusEnum;
use App\Contracts\Models\VisibilityEnum;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->paragraph(),
            'visibility' => Arr::random(VisibilityEnum::cases()),
            'status' => Arr::random(StatusEnum::cases()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'project_id' => Project::factory(),
        ];
    }
}
