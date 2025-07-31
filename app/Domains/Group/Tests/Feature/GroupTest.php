<?php

namespace App\Domains\Group\Tests\Feature;

use App\Domains\Group\Entities\Group;
use App\Domains\User\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_can_view_groups_index(): void
    {
        Group::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('groups.index'));

        $response->assertStatus(200);
        $response->assertViewIs('group::index');
        $response->assertViewHas('groups');
    }

    public function test_can_view_group_create_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('groups.create'));

        $response->assertStatus(200);
        $response->assertViewIs('group::create');
    }

    public function test_can_create_group(): void
    {
        $groupData = [
            'name' => 'Desenvolvimento',
            'description' => 'Projetos de desenvolvimento de software',
            'color' => '#3B82F6',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('groups.store'), $groupData);

        $response->assertRedirect();
        $this->assertDatabaseHas('groups', [
            'name' => 'Desenvolvimento',
            'description' => 'Projetos de desenvolvimento de software',
            'color' => '#3B82F6',
            'is_active' => true,
        ]);
    }

    public function test_can_view_group(): void
    {
        $group = Group::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('groups.show', $group->id));

        $response->assertStatus(200);
        $response->assertViewIs('group::show');
        $response->assertViewHas('group', $group);
    }

    public function test_can_view_group_edit_form(): void
    {
        $group = Group::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('groups.edit', $group->id));

        $response->assertStatus(200);
        $response->assertViewIs('group::edit');
        $response->assertViewHas('group', $group);
    }

    public function test_can_update_group(): void
    {
        $group = Group::factory()->create();
        $updateData = [
            'name' => 'Marketing Atualizado',
            'description' => 'Projetos de marketing digital',
            'color' => '#EF4444',
            'is_active' => false,
        ];

        $response = $this->actingAs($this->user)
            ->put(route('groups.update', $group->id), $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'name' => 'Marketing Atualizado',
            'description' => 'Projetos de marketing digital',
            'color' => '#EF4444',
            'is_active' => false,
        ]);
    }

    public function test_can_delete_group_without_projects(): void
    {
        $group = Group::factory()->create();

        $response = $this->actingAs($this->user)
            ->delete(route('groups.destroy', $group->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }

    public function test_group_name_must_be_unique(): void
    {
        Group::factory()->create(['name' => 'Desenvolvimento']);

        $groupData = [
            'name' => 'Desenvolvimento',
            'description' => 'Outro grupo com mesmo nome',
            'color' => '#3B82F6',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('groups.store'), $groupData);

        $response->assertSessionHasErrors('name');
    }

    public function test_group_color_must_be_valid_hex(): void
    {
        $groupData = [
            'name' => 'Teste',
            'description' => 'Teste de cor inválida',
            'color' => 'invalid-color',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('groups.store'), $groupData);

        $response->assertSessionHasErrors('color');
    }
} 