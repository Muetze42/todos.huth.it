<?php

// @codingStandardsIgnoreStart

namespace Tests\Feature;

use App\Contracts\VisibilityEnum;
use App\Models\Project;
use App\Models\User;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    public function test_guest_authorized_for_public_project(): void
    {
        /* @var \App\Models\Project $project */
        $project = Project::factory()->create([
            'visibility' => VisibilityEnum::GUEST,
        ]);

        $this->assertTrue($project->visibility->authorized(null));
    }

    public function test_guest_can_not_see_user_project(): void
    {
        /* @var \App\Models\Project $project */
        $project = Project::factory()->create([
            'visibility' => VisibilityEnum::USER,
        ]);

        $this->assertFalse($project->visibility->authorized(null));
    }

    public function test_guest_can_not_see_admin_project(): void
    {
        /* @var \App\Models\Project $project */
        $project = Project::factory()->create([
            'visibility' => VisibilityEnum::ADMIN,
        ]);

        $this->assertFalse($project->visibility->authorized(null));
    }

    public function test_user_authorized_for_public_project(): void
    {
        /* @var \App\Models\Project $project */
        $project = Project::factory()->create([
            'visibility' => VisibilityEnum::GUEST,
        ]);
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->assertTrue($project->visibility->authorized($user));
    }

    public function test_user_authorized_for_user_project(): void
    {
        /* @var \App\Models\Project $project */
        $project = Project::factory()->create([
            'visibility' => VisibilityEnum::USER,
        ]);
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->assertTrue($project->visibility->authorized($user));
    }

    public function test_user_can_not_see_admin_project(): void
    {
        /* @var \App\Models\Project $project */
        $project = Project::factory()->create([
            'visibility' => VisibilityEnum::ADMIN,
        ]);
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->assertFalse($project->visibility->authorized($user));
    }

    public function test_admin_authorized_for_public_project(): void
    {
        /* @var \App\Models\Project $project */
        $project = Project::factory()->create([
            'visibility' => VisibilityEnum::GUEST,
        ]);
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->assertTrue($project->visibility->authorized($user));
    }

    public function test_admin_authorized_for_user_project(): void
    {
        /* @var \App\Models\Project $project */
        $project = Project::factory()->create([
            'visibility' => VisibilityEnum::USER,
        ]);
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->assertTrue($project->visibility->authorized($user));
    }

    public function test_admin_authorized_for_admin_project(): void
    {
        /* @var \App\Models\Project $project */
        $project = Project::factory()->create([
            'visibility' => VisibilityEnum::ADMIN,
        ]);
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->assertTrue($project->visibility->authorized($user));
    }
}
