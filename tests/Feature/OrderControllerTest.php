<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Order;
use App\Models\OrderDetails;
use Gloudemans\Shoppingcart\Facades\Cart;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_display_pending_orders_page()
    {
        $orders = Order::factory(3)->create(['order_status' => 'pending']);

        $response = $this->get(route('order.pendingOrders'));

        $response->assertStatus(200)
            ->assertViewIs('orders.pending-orders')
            ->assertViewHas('orders')
            ->assertSee($orders[0]->invoice_no)
            ->assertSee($orders[1]->invoice_no)
            ->assertSee($orders[2]->invoice_no);
    }

    /** @test */
    public function it_can_display_complete_orders_page()
    {
        $orders = Order::factory(3)->create(['order_status' => 'complete']);

        $response = $this->get(route('order.completeOrders'));

        $response->assertStatus(200)
            ->assertViewIs('orders.complete-orders')
            ->assertViewHas('orders')
            ->assertSee($orders[0]->invoice_no)
            ->assertSee($orders[1]->invoice_no)
            ->assertSee($orders[2]->invoice_no);
    }

    /** @test */
    public function it_can_display_due_orders_page()
    {
        $orders = Order::factory(3)->create(['due' => 50]);

        $response = $this->get(route('order.dueOrders'));

        $response->assertStatus(200)
            ->assertViewIs('orders.due-orders')
            ->assertViewHas('orders')
            ->assertSee($orders[0]->invoice_no)
            ->assertSee($orders[1]->invoice_no)
            ->assertSee($orders[2]->invoice_no);
    }

    /** @test */
    public function it_can_display_due_order_details_page()
    {
        $order = Order::factory()->create(['due' => 50]);
        $orderDetails = OrderDetails::factory(3)->create(['order_id' => $order->id]);

        $response = $this->get(route('order.dueOrderDetails', ['order_id' => $order->id]));

        $response->assertStatus(200)
            ->assertViewIs('orders.details-due-order')
            ->assertViewHas('order', $order)
            ->assertViewHas('orderDetails', $orderDetails->toArray());
    }

    /** @test */
    public function it_can_display_order_details_page()
    {
        $order = Order::factory()->create();
        $orderDetails = OrderDetails::factory(3)->create(['order_id' => $order->id]);

        $response = $this->get(route('order.orderDetails', ['order_id' => $order->id]));

        $response->assertStatus(200)
            ->assertViewIs('orders.details-order')
            ->assertViewHas('order', $order)
            ->assertViewHas('orderDetails', $orderDetails->toArray());
    }

    /** @test */
    public function it_can_create_order()
    {
        $this->withoutExceptionHandling();

        $products = Cart::add(1, 'Product 1', 2, 20.00);
        $requestData = [
            'customer_id' => 1,
            'payment_type' => 'Cash',
            'pay' => 50.00,
        ];

        $response = $this->post(route('order.createOrder'), $requestData);

        $response->assertRedirect(route('order.pendingOrders'))
            ->assertSessionHas('success', 'Order has been created!');

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_details', $products->count());
    }

    /** @test */
    public function it_can_update_order_status()
    {
        $order = Order::factory()->create(['order_status' => 'pending']);
        $requestData = ['id' => $order->id];

        $response = $this->post(route('order.updateOrder'), $requestData);

        $response->assertRedirect(route('order.completeOrders'))
            ->assertSessionHas('success', 'Order has been completed!');

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'order_status' => 'complete']);
    }

    /** @test */
    public function it_can_update_due_order()
    {
        $order = Order::factory()->create(['due' => 50]);
        $requestData = ['id' => $order->id, 'pay' => 30.00];

        $response = $this->post(route('order.updateDueOrder'), $requestData);

        $response->assertRedirect(route('order.dueOrders'))
            ->assertSessionHas('success', 'Due amount has been updated!');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'due' => 20.00,
            'pay' => 80.00,
        ]);
    }

    /** @test */
    public function it_can_download_invoice()
    {
        $order = Order::factory()->create();
        $orderDetails = OrderDetails::factory(3)->create(['order_id' => $order->id]);

        $response = $this->get(route('order.downloadInvoice', ['order_id' => $order->id]));

        $response->assertStatus(200)
            ->assertViewIs('orders.print-invoice')
            ->assertViewHas('order', $order)
            ->assertViewHas('orderDetails', $orderDetails->toArray());
    }
}
