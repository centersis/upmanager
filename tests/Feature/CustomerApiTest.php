<?php

namespace Tests\Feature;

use App\Domains\Customer\Entities\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_customer(): void
    {
        $response = $this->postJson('/api/customers', [
            'name' => 'Empresa X',
            'status' => 'active',
        ]);

        $response->assertCreated()
                 ->assertJsonFragment(['name' => 'Empresa X']);

        $this->assertDatabaseHas('customers', ['name' => 'Empresa X']);
    }

    public function test_can_list_customers(): void
    {
        Customer::factory()->count(3)->create();

        $response = $this->getJson('/api/customers');

        $response->assertOk()->assertJsonCount(3);
    }

    public function test_can_show_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->getJson("/api/customers/{$customer->id}");

        $response->assertOk()->assertJsonFragment(['id' => $customer->id]);
    }

    public function test_can_update_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->putJson("/api/customers/{$customer->id}", [
            'name' => 'Empresa Y',
        ]);

        $response->assertOk()->assertJsonFragment(['name' => 'Empresa Y']);
        $this->assertDatabaseHas('customers', ['id' => $customer->id, 'name' => 'Empresa Y']);
    }

    public function test_can_delete_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->deleteJson("/api/customers/{$customer->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
} 