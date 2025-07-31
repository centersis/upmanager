<?php

namespace App\Domains\Project\Tests\Feature;

use App\Domains\Customer\Entities\Customer;
use App\Domains\Group\Entities\Group;
use App\Domains\Project\Entities\Project;
use App\Domains\User\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectGroupIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_project_create_page_shows_groups(): void
    {
        $group = Group::factory()->active()->create(['name' => 'Test Group']);
        
        // Garantir que o grupo foi criado como ativo
        $this->assertDatabaseHas('groups', ['name' => 'Test Group', 'is_active' => true]);

        $response = $this->actingAs($this->user)->get(route('projects.create'));

        $response->assertStatus(200)
            ->assertSee(__('projects.new_group'))
            ->assertSee(__('projects.manage_groups'));
            
        // Verificar se o grupo aparece no select (pode estar como option value ou text)
        $response->assertSee('Test Group', false);
    }

    public function test_project_edit_page_shows_groups(): void
    {
        $group = Group::factory()->active()->create(['name' => 'Test Group']);
        $customer = Customer::factory()->create();
        $project = Project::factory()->create(['group_id' => $group->id]);
        $project->customers()->attach($customer->id);
        
        // Garantir que os dados foram criados
        $this->assertDatabaseHas('groups', ['name' => 'Test Group', 'is_active' => true]);
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'group_id' => $group->id]);

        $response = $this->actingAs($this->user)->get(route('projects.edit', $project->id));

        $response->assertStatus(200)
            ->assertSee(__('projects.new_group'), false)  // Usar HTML bruto para encontrar o botão
            ->assertSee(__('projects.manage_groups'), false);
        
        // Verificar se o grupo aparece no select (pode estar como selected)
        $response->assertSee('Test Group', false);
    }

    public function test_can_create_project_with_group(): void
    {
        $group = Group::factory()->active()->create();
        $customer = Customer::factory()->create();

        $projectData = [
            'name' => 'Test Project with Group',
            'group_id' => $group->id,
            'status' => 'active',
            'customer_ids' => [$customer->id],
        ];

        $response = $this->actingAs($this->user)
            ->post(route('projects.store'), $projectData);

        $response->assertRedirect();
        
        $project = Project::where('name', 'Test Project with Group')->first();
        $this->assertNotNull($project);
        $this->assertEquals($group->id, $project->group_id);
        $this->assertTrue($project->customers->contains($customer));
    }

    public function test_can_update_project_group(): void
    {
        $group1 = Group::factory()->active()->create(['name' => 'Group 1']);
        $group2 = Group::factory()->active()->create(['name' => 'Group 2']);
        $customer = Customer::factory()->create();
        
        $project = Project::factory()->create(['group_id' => $group1->id]);
        $project->customers()->attach($customer->id);

        $updateData = [
            'name' => $project->name,
            'group_id' => $group2->id,
            'status' => 'active',
            'customer_ids' => [$customer->id],
        ];

        $response = $this->actingAs($this->user)
            ->put(route('projects.update', $project->id), $updateData);

        $response->assertRedirect();
        
        $project->refresh();
        $this->assertEquals($group2->id, $project->group_id);
    }

    public function test_can_remove_group_from_project(): void
    {
        $group = Group::factory()->active()->create();
        $customer = Customer::factory()->create();
        
        $project = Project::factory()->create(['group_id' => $group->id]);
        $project->customers()->attach($customer->id);

        $updateData = [
            'name' => $project->name,
            'group_id' => null, // Remove group
            'status' => 'active',
            'customer_ids' => [$customer->id],
        ];

        $response = $this->actingAs($this->user)
            ->put(route('projects.update', $project->id), $updateData);

        $response->assertRedirect();
        
        $project->refresh();
        $this->assertNull($project->group_id);
    }

    public function test_group_relationship_is_loaded_correctly(): void
    {
        $group = Group::factory()->active()->create(['name' => 'Development']);
        $customer = Customer::factory()->create();
        
        $project = Project::factory()->create(['group_id' => $group->id]);
        $project->customers()->attach($customer->id);

        // Test that relationship loads correctly
        $project->load('group');
        $this->assertNotNull($project->group);
        $this->assertEquals('Development', $project->group->name);

        // Test that group knows about its projects
        $group->load('projects');
        $this->assertTrue($group->projects->contains($project));
    }

    public function test_deleting_group_with_projects_should_fail(): void
    {
        $group = Group::factory()->active()->create();
        $customer = Customer::factory()->create();
        
        $project = Project::factory()->create(['group_id' => $group->id]);
        $project->customers()->attach($customer->id);

        $response = $this->actingAs($this->user)
            ->delete(route('groups.destroy', $group->id));

        $response->assertRedirect()
            ->assertSessionHas('error');
        
        // Group should still exist
        $this->assertDatabaseHas('groups', ['id' => $group->id]);
    }

    public function test_can_create_group_via_api_for_modal(): void
    {
        $groupData = [
            'name' => 'New Modal Group',
            'description' => 'Created from project modal',
            'color' => '#FF5733',
            'is_active' => true,
        ];

        $response = $this->postJson('/api/groups', $groupData);

        $response->assertCreated()
            ->assertJsonFragment(['name' => 'New Modal Group']);

        $this->assertDatabaseHas('groups', $groupData);
    }

    public function test_group_validation_works_in_api(): void
    {
        // Create a group with same name first
        Group::factory()->active()->create(['name' => 'Duplicate Name']);

        $groupData = [
            'name' => 'Duplicate Name',
            'description' => 'This should fail',
            'color' => '#FF5733',
            'is_active' => true,
        ];

        $response = $this->postJson('/api/groups', $groupData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }
} 