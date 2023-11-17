<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Customer;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_display_customer_index_page()
    {
        // Arrange
        $customers = Customer::factory(3)->create();

        // Act
        $response = $this->get(route('customers.index'));

        // Assert
        $response->assertStatus(200)
            ->assertViewIs('customers.index')
            ->assertViewHas('customers')
            ->assertSee($customers[0]->name)
            ->assertSee($customers[1]->name)
            ->assertSee($customers[2]->name);
    }

    /** @test */
    public function it_can_display_create_customer_form()
    {
        // Act
        $response = $this->get(route('customers.create'));

        // Assert
        $response->assertStatus(200)
            ->assertViewIs('customers.create');
    }

    /** @test */
    public function it_can_store_a_new_customer()
    {
        // Arrange
        $customerData = Customer::factory()->make()->toArray();

        // Act
        $response = $this->post(route('customers.store'), $customerData);

        // Assert
        $response->assertRedirect(route('customers.index'))
            ->assertSessionHas('success', 'New customer has been created!');

        $this->assertDatabaseHas('customers', $customerData);
    }

    /** @test */
    public function it_can_display_edit_customer_form()
    {
        // Arrange
        $customer = Customer::factory()->create();

        // Act
        $response = $this->get(route('customers.edit', $customer));

        // Assert
        $response->assertStatus(200)
            ->assertViewIs('customers.edit')
            ->assertViewHas('customer', $customer);
    }

    /** @test */
    public function it_can_update_customer_information()
    {
        // Arrange
        $customer = Customer::factory()->create();
        $updatedCustomerData = Customer::factory()->make()->toArray();

        // Act
        $response = $this->put(route('customers.update', $customer), $updatedCustomerData);

        // Assert
        $response->assertRedirect(route('customers.index'))
            ->assertSessionHas('success', 'Customer has been updated!');

        $this->assertDatabaseHas('customers', $updatedCustomerData);
        $this->assertDatabaseMissing('customers', $customer->toArray());
    }

    /** @test */
    public function it_can_delete_customer()
    {
        // Arrange
        $customer = Customer::factory()->create();

        // Act
        $response = $this->delete(route('customers.destroy', $customer));

        // Assert
        $response->assertRedirect()
            ->assertSessionHas('success', 'Customer has been deleted!');

        $this->assertDatabaseMissing('customers', $customer->toArray());
    }
}
