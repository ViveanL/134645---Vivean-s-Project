<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Customer;
use Gloudemans\Shoppingcart\Facades\Cart;

class PosControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_display_pos_index_page()
    {
        $products = Product::factory(3)->create();
        $customers = Customer::factory(3)->create();

        $response = $this->get(route('pos.index'));

        $response->assertStatus(200)
            ->assertViewIs('pos.index')
            ->assertViewHas('products', $products)
            ->assertViewHas('customers', $customers)
            ->assertViewHas('carts');
    }

    /** @test */
    public function it_can_add_product_to_cart()
    {
        $product = Product::factory()->create();
        $requestData = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
        ];

        $response = $this->post(route('pos.addCartItem'), $requestData);

        $response->assertRedirect(route('pos.index'))
            ->assertSessionHas('success', 'Product has been added to cart!');

        $this->assertNotEmpty(Cart::content());
    }

    /** @test */
    public function it_can_update_product_in_cart()
    {
        $product = Product::factory()->create();
        $rowId = Cart::add(['id' => $product->id, 'name' => $product->name, 'qty' => 1, 'price' => $product->price])->rowId;
        $requestData = ['qty' => 3];

        $response = $this->patch(route('pos.updateCartItem', ['rowId' => $rowId]), $requestData);

        $response->assertRedirect(route('pos.index'))
            ->assertSessionHas('success', 'Product has been updated from cart!');

        $this->assertEquals(3, Cart::get($rowId)->qty);
    }

    /** @test */
    public function it_can_delete_product_from_cart()
    {
        $product = Product::factory()->create();
        $rowId = Cart::add(['id' => $product->id, 'name' => $product->name, 'qty' => 1, 'price' => $product->price])->rowId;

        $response = $this->delete(route('pos.deleteCartItem', ['rowId' => $rowId]));

        $response->assertRedirect(route('pos.index'))
            ->assertSessionHas('success', 'Product has been deleted from cart!');

        $this->assertEmpty(Cart::content());
    }

    /** @test */
    public function it_can_display_create_invoice_page()
    {
        $customer = Customer::factory()->create();
        $product = Product::factory()->create();
        Cart::add(['id' => $product->id, 'name' => $product->name, 'qty' => 1, 'price' => $product->price]);

        $response = $this->post(route('pos.createInvoice'), ['customer_id' => $customer->id]);

        $response->assertStatus(200)
            ->assertViewIs('pos.create')
            ->assertViewHas('customer', $customer)
            ->assertViewHas('carts');
    }
}
