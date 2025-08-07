<?php

namespace App\Domains\Update\Tests\Feature;

use App\Domains\User\Entities\User;
use App\Domains\Customer\Entities\Customer;
use App\Domains\Project\Entities\Project;
use App\Domains\Update\Entities\Update;
use App\Domains\Group\Entities\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Customer $customer;
    protected Project $project;
    protected Group $group;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['is_active' => true]);
        $this->customer = Customer::factory()->create();
        $this->group = Group::factory()->create();
        $this->project = Project::factory()->create(['group_id' => $this->group->id]);
        $this->project->customers()->attach($this->customer);
    }

    public function test_can_list_updates(): void
    {
        Sanctum::actingAs($this->user);

        Update::factory()->count(5)->create([
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id,
        ]);

        $response = $this->getJson('/api/updates');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => [
                             'id',
                             'title',
                             'caption',
                             'description',
                             'status',
                             'views',
                             'hash',
                             'created_at',
                             'updated_at',
                             'project' => [
                                 'id',
                                 'name'
                             ],
                             'customer' => [
                                 'id',
                                 'name'
                             ]
                         ]
                     ],
                     'pagination' => [
                         'current_page',
                         'last_page',
                         'per_page',
                         'total'
                     ]
                 ])
                 ->assertJson(['success' => true]);

        $this->assertCount(5, $response->json('data'));
    }

    public function test_can_filter_updates_by_status(): void
    {
        Sanctum::actingAs($this->user);

        Update::factory()->count(3)->create([
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id,
            'status' => 'published'
        ]);

        Update::factory()->count(2)->create([
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id,
            'status' => 'draft'
        ]);

        $response = $this->getJson('/api/updates?status=published');

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
        
        foreach ($response->json('data') as $update) {
            $this->assertEquals('published', $update['status']);
        }
    }

    public function test_can_search_updates(): void
    {
        Sanctum::actingAs($this->user);

        Update::factory()->create([
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id,
            'title' => 'Nova funcionalidade de busca'
        ]);

        Update::factory()->create([
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id,
            'title' => 'Correção de bug'
        ]);

        $response = $this->getJson('/api/updates?search=busca');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertStringContainsString('busca', $response->json('data.0.title'));
    }

    public function test_can_create_update(): void
    {
        Sanctum::actingAs($this->user);

        $updateData = [
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id,
            'title' => 'Nova atualização',
            'caption' => 'Legenda da atualização',
            'description' => 'Descrição detalhada da atualização',
            'status' => 'published'
        ];

        $response = $this->postJson('/api/updates', $updateData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'id',
                         'title',
                         'caption',
                         'description',
                         'status',
                         'views',
                         'hash',
                         'created_at',
                         'updated_at',
                         'project',
                         'customer'
                     ]
                 ])
                 ->assertJson([
                     'success' => true,
                     'message' => 'Atualização criada com sucesso',
                     'data' => [
                         'title' => 'Nova atualização',
                         'caption' => 'Legenda da atualização',
                         'status' => 'published'
                     ]
                 ]);

        $this->assertDatabaseHas('updates', [
            'title' => 'Nova atualização',
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id
        ]);
    }

    // Global updates functionality was removed from the system

    public function test_create_update_validation(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/updates', [
            'title' => '', // Required field empty
            'status' => 'invalid_status' // Invalid status
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'status']);
    }

    public function test_can_show_update(): void
    {
        Sanctum::actingAs($this->user);

        $update = Update::factory()->create([
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id,
        ]);

        $response = $this->getJson("/api/updates/{$update->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'title',
                         'caption',
                         'description',
                         'status',
                         'views',
                         'hash',
                         'created_at',
                         'updated_at',
                         'project' => [
                             'id',
                             'name',
                             'description',
                             'status'
                         ],
                         'customer' => [
                             'id',
                             'name',
                             'email',
                             'status'
                         ]
                     ]
                 ])
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id' => $update->id,
                         'title' => $update->title
                     ]
                 ]);

        // Check that views were incremented
        $this->assertDatabaseHas('updates', [
            'id' => $update->id,
            'views' => 1
        ]);
    }

    public function test_show_nonexistent_update(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/updates/999');

        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Atualização não encontrada'
                 ]);
    }

    public function test_can_update_update(): void
    {
        Sanctum::actingAs($this->user);

        $update = Update::factory()->create([
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id,
            'title' => 'Título original'
        ]);

        $updateData = [
            'title' => 'Título atualizado',
            'status' => 'archived'
        ];

        $response = $this->putJson("/api/updates/{$update->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Atualização atualizada com sucesso',
                     'data' => [
                         'id' => $update->id,
                         'title' => 'Título atualizado',
                         'status' => 'archived'
                     ]
                 ]);

        $this->assertDatabaseHas('updates', [
            'id' => $update->id,
            'title' => 'Título atualizado',
            'status' => 'archived'
        ]);
    }

    public function test_can_delete_update(): void
    {
        Sanctum::actingAs($this->user);

        $update = Update::factory()->create([
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id,
        ]);

        $response = $this->deleteJson("/api/updates/{$update->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Atualização removida com sucesso'
                 ]);

        $this->assertDatabaseMissing('updates', ['id' => $update->id]);
    }

    public function test_can_get_updates_by_project(): void
    {
        Sanctum::actingAs($this->user);

        $anotherProject = Project::factory()->create(['group_id' => $this->group->id]);

        // Create updates for our project
        Update::factory()->count(3)->create([
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id,
        ]);

        // Create updates for another project
        Update::factory()->count(2)->create([
            'project_id' => $anotherProject->id,
            'customer_id' => $this->customer->id,
        ]);

        $response = $this->getJson("/api/updates/project/{$this->project->id}");

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
        
        foreach ($response->json('data') as $update) {
            // Project info is not included in byProject response to avoid redundancy
            $this->assertArrayNotHasKey('project', $update);
        }
    }

    public function test_can_get_updates_by_customer(): void
    {
        Sanctum::actingAs($this->user);

        $anotherCustomer = Customer::factory()->create();

        // Create updates for our customer
        Update::factory()->count(3)->create([
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id,
        ]);

        // Create updates for another customer
        Update::factory()->count(2)->create([
            'project_id' => $this->project->id,
            'customer_id' => $anotherCustomer->id,
        ]);

        $response = $this->getJson("/api/updates/customer/{$this->customer->id}");

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
        
        foreach ($response->json('data') as $update) {
            // Customer info is not included in byCustomer response to avoid redundancy
            $this->assertArrayNotHasKey('customer', $update);
        }
    }

    public function test_can_get_update_by_hash_public(): void
    {
        $update = Update::factory()->create([
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id,
            'hash' => 'test-hash-123'
        ]);

        // No authentication required for public hash access
        $response = $this->getJson("/api/updates/hash/test-hash-123");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id' => $update->id,
                         'title' => $update->title
                     ]
                 ]);

        // Check that views were incremented
        $this->assertDatabaseHas('updates', [
            'id' => $update->id,
            'views' => 1
        ]);
    }

    public function test_get_update_by_invalid_hash(): void
    {
        $response = $this->getJson('/api/updates/hash/invalid-hash');

        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Atualização não encontrada'
                 ]);
    }

    public function test_can_get_update_options(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/updates-options');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'projects' => [
                             '*' => [
                                 'id',
                                 'name',
                                 'status'
                             ]
                         ],
                         'customers' => [
                             '*' => [
                                 'id',
                                 'name',
                                 'status'
                             ]
                         ],
                         'statuses' => [
                             '*' => [
                                 'value',
                                 'label'
                             ]
                         ]
                     ]
                 ])
                 ->assertJson(['success' => true]);
    }

    public function test_endpoints_require_authentication(): void
    {
        $update = Update::factory()->create([
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id,
        ]);

        $protectedEndpoints = [
            ['method' => 'GET', 'url' => '/api/updates'],
            ['method' => 'POST', 'url' => '/api/updates'],
            ['method' => 'GET', 'url' => "/api/updates/{$update->id}"],
            ['method' => 'PUT', 'url' => "/api/updates/{$update->id}"],
            ['method' => 'DELETE', 'url' => "/api/updates/{$update->id}"],
            ['method' => 'GET', 'url' => "/api/updates/project/{$this->project->id}"],
            ['method' => 'GET', 'url' => "/api/updates/customer/{$this->customer->id}"],
            ['method' => 'GET', 'url' => '/api/updates-options'],
        ];

        foreach ($protectedEndpoints as $endpoint) {
            $response = $this->json($endpoint['method'], $endpoint['url']);
            $response->assertStatus(401);
        }
    }

    public function test_endpoints_require_active_user(): void
    {
        $inactiveUser = User::factory()->create(['is_active' => false]);
        Sanctum::actingAs($inactiveUser);

        $update = Update::factory()->create([
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id,
        ]);

        $response = $this->getJson('/api/updates');

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Sua conta está inativa. Entre em contato com o administrador.'
                 ]);
    }

    public function test_pagination_works_correctly(): void
    {
        Sanctum::actingAs($this->user);

        Update::factory()->count(25)->create([
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id,
        ]);

        // Test first page
        $response = $this->getJson('/api/updates?per_page=10');
        $response->assertStatus(200);
        
        $pagination = $response->json('pagination');
        $this->assertEquals(1, $pagination['current_page']);
        $this->assertEquals(10, $pagination['per_page']);
        $this->assertEquals(25, $pagination['total']);
        $this->assertEquals(3, $pagination['last_page']);
        $this->assertCount(10, $response->json('data'));

        // Test second page
        $response = $this->getJson('/api/updates?per_page=10&page=2');
        $response->assertStatus(200);
        
        $pagination = $response->json('pagination');
        $this->assertEquals(2, $pagination['current_page']);
        $this->assertCount(10, $response->json('data'));
    }

    public function test_respects_max_per_page_limit(): void
    {
        Sanctum::actingAs($this->user);

        Update::factory()->count(150)->create([
            'project_id' => $this->project->id,
            'customer_id' => $this->customer->id,
        ]);

        $response = $this->getJson('/api/updates?per_page=200'); // Requesting more than max (100)
        $response->assertStatus(200);
        
        $pagination = $response->json('pagination');
        $this->assertEquals(100, $pagination['per_page']); // Should be capped at 100
        $this->assertCount(100, $response->json('data'));
    }
}