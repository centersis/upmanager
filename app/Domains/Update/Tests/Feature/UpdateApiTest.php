<?php

namespace App\Domains\Update\Tests\Feature;

use App\Domains\Customer\Entities\Customer;
use App\Domains\Project\Entities\Project;
use App\Domains\Update\Entities\Update;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_update(): void
    {
        $project = Project::factory()->create();
        $customer = Customer::factory()->create();
        
        $payload = [
            'project_id' => $project->id,
            'customer_id' => $customer->id,
            'title' => 'Bug fixes',
            'caption' => 'Correções gerais',
        ];

        $response = $this->postJson('/api/updates', $payload);

        $response->assertCreated()->assertJsonFragment(['title' => 'Bug fixes']);
        $this->assertDatabaseHas('updates', ['title' => 'Bug fixes']);
    }

    public function test_can_list_updates(): void
    {
        Update::factory()->count(4)->create();

        $response = $this->getJson('/api/updates');

        $response->assertOk()->assertJsonCount(4);
    }

    public function test_can_show_update(): void
    {
        $update = Update::factory()->create();

        $response = $this->getJson("/api/updates/{$update->id}");

        $response->assertOk()->assertJsonFragment(['id' => $update->id]);
    }

    public function test_can_update_update(): void
    {
        $update = Update::factory()->create();
        $newTitle = 'Melhorias na performance';

        $response = $this->putJson("/api/updates/{$update->id}", [
            'title' => $newTitle,
        ]);

        $response->assertOk()->assertJsonFragment(['title' => $newTitle]);
        $this->assertDatabaseHas('updates', ['id' => $update->id, 'title' => $newTitle]);
    }

    public function test_can_delete_update(): void
    {
        $update = Update::factory()->create();

        $response = $this->deleteJson("/api/updates/{$update->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('updates', ['id' => $update->id]);
    }
} 