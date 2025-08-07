<?php

namespace App\Domains\Dashboard\Tests\Feature;

use App\Domains\User\Entities\User;
use App\Domains\Customer\Entities\Customer;
use App\Domains\Project\Entities\Project;
use App\Domains\Update\Entities\Update;
use App\Domains\Group\Entities\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DashboardApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create([
            'is_active' => true,
        ]);
    }

    public function test_dashboard_stats_endpoint(): void
    {
        Sanctum::actingAs($this->user);

        // Create test data
        Customer::factory()->count(5)->create(['status' => 'active']);
        Customer::factory()->count(2)->create(['status' => 'inactive']);
        
        $group = Group::factory()->create();
        Project::factory()->count(3)->create(['status' => 'active', 'group_id' => $group->id]);
        Project::factory()->count(2)->create(['status' => 'completed', 'group_id' => $group->id]);
        Project::factory()->count(1)->create(['status' => 'on_hold', 'group_id' => $group->id]);
        
        Update::factory()->count(8)->create();

        $response = $this->getJson('/api/dashboard/stats');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'customers' => [
                             'total',
                             'active',
                             'inactive'
                         ],
                         'projects' => [
                             'total',
                             'active',
                             'completed',
                             'on_hold'
                         ],
                         'updates' => [
                             'total',
                             'this_month',
                             'this_week'
                         ],
                         'groups' => [
                             'total'
                         ]
                     ]
                 ])
                 ->assertJson([
                     'success' => true
                 ]);

        // Verify the data structure and consistency
        $data = $response->json('data');
        $this->assertGreaterThanOrEqual(7, $data['customers']['total']);
        $this->assertGreaterThanOrEqual(5, $data['customers']['active']);
        $this->assertEquals(2, $data['customers']['inactive']);
        
        $this->assertGreaterThanOrEqual(6, $data['projects']['total']);
        $this->assertGreaterThanOrEqual(3, $data['projects']['active']);
        $this->assertEquals(2, $data['projects']['completed']);
        $this->assertEquals(1, $data['projects']['on_hold']);
        
        $this->assertGreaterThanOrEqual(8, $data['updates']['total']);
        $this->assertGreaterThanOrEqual(1, $data['groups']['total']);
    }

    public function test_dashboard_recent_updates_endpoint(): void
    {
        Sanctum::actingAs($this->user);

        // Create test data
        $customer = Customer::factory()->create();
        $group = Group::factory()->create();
        $project = Project::factory()->create(['group_id' => $group->id]);
        $project->customers()->attach($customer);
        
        Update::factory()->count(15)->create([
            'project_id' => $project->id,
            'customer_id' => $customer->id,
        ]);

        $response = $this->getJson('/api/dashboard/recent-updates?limit=5');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => [
                             'id',
                             'title',
                             'description',
                             'type',
                             'status',
                             'is_global',
                             'created_at',
                             'project' => [
                                 'id',
                                 'name'
                             ],
                             'customer' => [
                                 'id',
                                 'name'
                             ]
                         ]
                     ]
                 ])
                 ->assertJson([
                     'success' => true
                 ]);

        $this->assertCount(5, $response->json('data'));
    }

    public function test_dashboard_recent_projects_endpoint(): void
    {
        Sanctum::actingAs($this->user);

        // Create test data
        $customer = Customer::factory()->create();
        $group = Group::factory()->create();
        $projects = Project::factory()->count(8)->create(['group_id' => $group->id]);
        
        foreach ($projects as $project) {
            $project->customers()->attach($customer);
        }

        $response = $this->getJson('/api/dashboard/recent-projects?limit=3');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => [
                             'id',
                             'name',
                             'description',
                             'status',
                             'start_date',
                             'end_date',
                             'created_at',
                             'customers' => [
                                 '*' => [
                                     'id',
                                     'name'
                                 ]
                             ],
                             'group' => [
                                 'id',
                                 'name'
                             ]
                         ]
                     ]
                 ])
                 ->assertJson([
                     'success' => true
                 ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_dashboard_activity_timeline_endpoint(): void
    {
        Sanctum::actingAs($this->user);

        // Create test data
        $customer = Customer::factory()->create();
        $group = Group::factory()->create();
        $project = Project::factory()->create(['group_id' => $group->id]);
        $project->customers()->attach($customer);
        
        Update::factory()->count(3)->create([
            'project_id' => $project->id,
            'customer_id' => $customer->id,
        ]);

        $response = $this->getJson('/api/dashboard/activity-timeline?days=7&limit=10');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => [
                             'type',
                             'title',
                             'description',
                             'date',
                             'project',
                             'customer'
                         ]
                     ]
                 ])
                 ->assertJson([
                     'success' => true
                 ]);

        // Should have at least updates, projects, and customers activities
        $activities = $response->json('data');
        $this->assertGreaterThan(0, count($activities));
    }

    public function test_dashboard_overview_endpoint(): void
    {
        Sanctum::actingAs($this->user);

        // Create comprehensive test data
        Customer::factory()->count(3)->create(['status' => 'active']);
        Customer::factory()->count(1)->create(['status' => 'inactive']);
        
        $group = Group::factory()->create();
        $projects = Project::factory()->count(4)->create(['group_id' => $group->id]);
        
        foreach ($projects as $project) {
            $customer = Customer::factory()->create();
            $project->customers()->attach($customer);
            
            Update::factory()->count(2)->create([
                'project_id' => $project->id,
                'customer_id' => $customer->id,
            ]);
        }

        $response = $this->getJson('/api/dashboard/overview');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'stats' => [
                             'customers',
                             'projects',
                             'updates',
                             'groups'
                         ],
                         'recent_updates' => [
                             '*' => [
                                 'id',
                                 'title',
                                 'description',
                                 'type',
                                 'status',
                                 'created_at'
                             ]
                         ],
                         'recent_projects' => [
                             '*' => [
                                 'id',
                                 'name',
                                 'description',
                                 'status',
                                 'created_at'
                             ]
                         ],
                         'recent_activity' => [
                             '*' => [
                                 'type',
                                 'title',
                                 'description',
                                 'date'
                             ]
                         ]
                     ]
                 ])
                 ->assertJson([
                     'success' => true
                 ]);

        // Check that we have limited results
        $this->assertLessThanOrEqual(5, count($response->json('data.recent_updates')));
        $this->assertLessThanOrEqual(5, count($response->json('data.recent_projects')));
        $this->assertLessThanOrEqual(10, count($response->json('data.recent_activity')));
    }

    public function test_dashboard_endpoints_require_authentication(): void
    {
        $endpoints = [
            '/api/dashboard/overview',
            '/api/dashboard/stats',
            '/api/dashboard/recent-updates',
            '/api/dashboard/recent-projects',
            '/api/dashboard/activity-timeline',
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->getJson($endpoint);
            $response->assertStatus(401);
        }
    }

    public function test_dashboard_endpoints_require_active_user(): void
    {
        $inactiveUser = User::factory()->create([
            'is_active' => false,
        ]);

        Sanctum::actingAs($inactiveUser);

        $endpoints = [
            '/api/dashboard/overview',
            '/api/dashboard/stats',
            '/api/dashboard/recent-updates',
            '/api/dashboard/recent-projects',
            '/api/dashboard/activity-timeline',
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->getJson($endpoint);
            $response->assertStatus(403)
                     ->assertJson([
                         'success' => false,
                         'message' => 'Sua conta está inativa. Entre em contato com o administrador.'
                     ]);
        }
    }

    public function test_recent_updates_respects_limit_parameter(): void
    {
        Sanctum::actingAs($this->user);

        $customer = Customer::factory()->create();
        $group = Group::factory()->create();
        $project = Project::factory()->create(['group_id' => $group->id]);
        $project->customers()->attach($customer);
        
        Update::factory()->count(20)->create([
            'project_id' => $project->id,
            'customer_id' => $customer->id,
        ]);

        // Test default limit
        $response = $this->getJson('/api/dashboard/recent-updates');
        $this->assertCount(10, $response->json('data'));

        // Test custom limit
        $response = $this->getJson('/api/dashboard/recent-updates?limit=3');
        $this->assertCount(3, $response->json('data'));

        // Test max limit (should cap at 50)
        $response = $this->getJson('/api/dashboard/recent-updates?limit=100');
        $this->assertLessThanOrEqual(50, count($response->json('data')));
    }

    public function test_recent_projects_respects_limit_parameter(): void
    {
        Sanctum::actingAs($this->user);

        $group = Group::factory()->create();
        Project::factory()->count(15)->create(['group_id' => $group->id]);

        // Test default limit
        $response = $this->getJson('/api/dashboard/recent-projects');
        $this->assertCount(10, $response->json('data'));

        // Test custom limit
        $response = $this->getJson('/api/dashboard/recent-projects?limit=5');
        $this->assertCount(5, $response->json('data'));
    }

    public function test_activity_timeline_respects_days_parameter(): void
    {
        Sanctum::actingAs($this->user);

        // Create old data (should not appear in 7-day timeline)
        $oldCustomer = Customer::factory()->create(['created_at' => now()->subDays(10)]);
        
        // Create recent data (should appear in 7-day timeline)
        $recentCustomer = Customer::factory()->create(['created_at' => now()->subDays(3)]);

        $response = $this->getJson('/api/dashboard/activity-timeline?days=7');
        $response->assertStatus(200);

        $activities = $response->json('data');
        
        // Check that only recent activities are included
        $recentActivities = collect($activities)->filter(function ($activity) {
            return strtotime($activity['date']) >= now()->subDays(7)->timestamp;
        });

        $this->assertEquals(count($activities), $recentActivities->count());
    }
}
