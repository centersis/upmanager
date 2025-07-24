<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_project(): void
    {
        $customer = Customer::factory()->create();
        $payload = [
            'name' => 'Projeto Alpha',
            'hash' => Str::uuid()->toString(),
            'customer_ids' => [$customer->id],
        ];

        $response = $this->postJson('/api/projects', $payload);

        $response->assertCreated()->assertJsonFragment(['name' => 'Projeto Alpha']);
        $this->assertDatabaseHas('projects', ['name' => 'Projeto Alpha']);
    }

    public function test_can_list_projects(): void
    {
        Project::factory()->count(2)->create();

        $response = $this->getJson('/api/projects');

        $response->assertOk()->assertJsonCount(2);
    }

    public function test_can_show_project(): void
    {
        $project = Project::factory()->create();

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertOk()->assertJsonFragment(['id' => $project->id]);
    }

    public function test_can_update_project(): void
    {
        $project = Project::factory()->create();
        $newName = 'Projeto Beta';

        $response = $this->putJson("/api/projects/{$project->id}", ['name' => $newName]);

        $response->assertOk()->assertJsonFragment(['name' => $newName]);
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => $newName]);
    }

    public function test_can_delete_project(): void
    {
        $project = Project::factory()->create();

        $response = $this->deleteJson("/api/projects/{$project->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
} 