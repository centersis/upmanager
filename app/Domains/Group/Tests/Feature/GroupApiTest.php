<?php

namespace App\Domains\Group\Tests\Feature;

use App\Domains\Group\Entities\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_groups_via_api(): void
    {
        Group::factory()->count(3)->create();

        $response = $this->getJson('/api/groups');

        $response->assertOk()->assertJsonCount(3);
    }

    public function test_can_create_group_via_api(): void
    {
        $groupData = [
            'name' => 'API Group',
            'description' => 'Created via API',
            'color' => '#3B82F6',
            'is_active' => true,
        ];

        $response = $this->postJson('/api/groups', $groupData);

        $response->assertCreated()
            ->assertJsonFragment(['name' => 'API Group']);

        $this->assertDatabaseHas('groups', $groupData);
    }

    public function test_can_show_group_via_api(): void
    {
        $group = Group::factory()->create();

        $response = $this->getJson("/api/groups/{$group->id}");

        $response->assertOk()->assertJsonFragment(['id' => $group->id]);
    }

    public function test_can_update_group_via_api(): void
    {
        $group = Group::factory()->create();
        $updateData = [
            'name' => 'Updated API Group',
            'description' => 'Updated via API',
            'color' => '#EF4444',
            'is_active' => false,
        ];

        $response = $this->putJson("/api/groups/{$group->id}", $updateData);

        $response->assertOk()->assertJsonFragment(['name' => 'Updated API Group']);
        $this->assertDatabaseHas('groups', array_merge(['id' => $group->id], $updateData));
    }

    public function test_can_delete_group_via_api(): void
    {
        $group = Group::factory()->create();

        $response = $this->deleteJson("/api/groups/{$group->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }

    public function test_can_list_active_groups_via_api(): void
    {
        Group::factory()->active()->count(2)->create();
        Group::factory()->inactive()->count(1)->create();

        $response = $this->getJson('/api/groups-active');

        $response->assertOk()->assertJsonCount(2, 'data');
    }

    public function test_returns_404_for_non_existent_group(): void
    {
        $response = $this->getJson('/api/groups/999');

        $response->assertNotFound();
    }

    public function test_validates_required_fields_on_create(): void
    {
        $response = $this->postJson('/api/groups', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }
}
