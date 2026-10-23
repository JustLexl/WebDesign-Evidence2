<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Department;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderAndUserTest extends TestCase
{
    use DatabaseTransactions;

    public function test_guest_can_search_order_by_invoice()
    {
        $response = $this->get('/?invoice_number=INV-1001');
        $response->assertStatus(200);
        $response->assertSee('INV-1001');
        $response->assertSee('Delivered');
        $response->assertSee('Delivery Photo Evidence');

        $responseProcess = $this->get('/?invoice_number=INV-1003');
        $responseProcess->assertStatus(200);
        $responseProcess->assertSee('In process');
        $responseProcess->assertSee('Process Information');
    }

    public function test_guest_cannot_access_dashboard_or_orders()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        $responseOrders = $this->get('/orders');
        $responseOrders->assertRedirect('/login');

        $responseUsers = $this->get('/users');
        $responseUsers->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_dashboard()
    {
        $admin = User::where('email', 'admin@halcon.com')->first();
        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('Orders');
        $response->assertSee('Users');
    }

    public function test_user_management_crud()
    {
        $admin = User::where('email', 'admin@halcon.com')->first();
        $warehouseDept = Department::where('name', 'Warehouse')->first();

        $response = $this->actingAs($admin)->get('/users');
        $response->assertStatus(200);
        $response->assertSee('Carlos Sales');
        $response->assertSee('Inactive User');

        $newUserData = [
            'name' => 'Test Employee',
            'email' => 'employee@halcon.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'department_id' => $warehouseDept->id,
            'is_active' => 1,
        ];

        $postResponse = $this->actingAs($admin)->post('/users', $newUserData);
        $postResponse->assertRedirect('/users');

        $createdUser = User::where('email', 'employee@halcon.com')->first();
        $this->assertNotNull($createdUser);
        $this->assertEquals($warehouseDept->id, $createdUser->department_id);
        $this->assertTrue((bool)$createdUser->is_active);

        $updateData = [
            'name' => 'Test Employee Updated',
            'email' => 'employee@halcon.com',
            'department_id' => $warehouseDept->id,
            'is_active' => 0,
        ];

        $putResponse = $this->actingAs($admin)->put('/users/' . $createdUser->id, $updateData);
        $putResponse->assertRedirect('/users');

        $createdUser->refresh();
        $this->assertEquals('Test Employee Updated', $createdUser->name);
        $this->assertFalse((bool)$createdUser->is_active);
    }

    public function test_inactive_user_cannot_login()
    {
        $response = $this->post('/login', [
            'email' => 'inactive@halcon.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
    }

    public function test_orders_flow_status_photos_and_soft_delete_restore()
    {
        $salesUser = User::where('email', 'sales@halcon.com')->first();

        $orderData = [
            'invoice_number' => 'INV-9999',
            'customer_number' => 'CUST-999',
            'customer_name' => 'Constructora Test S.A.',
            'fiscal_data' => 'RFC: CTS990101XX1',
            'order_datetime' => '2026-05-10 10:00:00',
            'delivery_address' => 'Av. Tecnologico 500, Monterrey',
            'notes' => 'Urgent materials',
        ];

        $createResponse = $this->actingAs($salesUser)->post('/orders', $orderData);
        $createResponse->assertRedirect('/orders');

        $order = Order::where('invoice_number', 'INV-9999')->first();
        $this->assertNotNull($order);
        $this->assertEquals('Ordered', $order->status);

        $statusProcess = $this->actingAs($salesUser)->post('/orders/' . $order->id . '/status', [
            'status' => 'In process',
            'evidence_notes' => 'Materials picked from aisle B',
        ]);
        $statusProcess->assertRedirect('/orders/' . $order->id);
        $order->refresh();
        $this->assertEquals('In process', $order->status);
        $this->assertNotNull($order->process_date);

        $fakePhoto = UploadedFile::fake()->image('loaded_unit.jpg');
        $statusRoute = $this->actingAs($salesUser)->post('/orders/' . $order->id . '/status', [
            'status' => 'In route',
            'evidence_photo' => $fakePhoto,
            'evidence_notes' => 'Truck 4 loaded',
        ]);
        $statusRoute->assertRedirect('/orders/' . $order->id);
        $order->refresh();
        $this->assertEquals('In route', $order->status);
        $this->assertNotNull($order->route_photo);

        $fakeDeliveryPhoto = UploadedFile::fake()->image('delivered_evidence.jpg');
        $statusDelivered = $this->actingAs($salesUser)->post('/orders/' . $order->id . '/status', [
            'status' => 'Delivered',
            'evidence_photo' => $fakeDeliveryPhoto,
            'evidence_notes' => 'Delivered on site',
        ]);
        $statusDelivered->assertRedirect('/orders/' . $order->id);
        $order->refresh();
        $this->assertEquals('Delivered', $order->status);
        $this->assertNotNull($order->delivery_photo);

        $deleteResponse = $this->actingAs($salesUser)->delete('/orders/' . $order->id);
        $deleteResponse->assertRedirect('/orders');
        $this->assertSoftDeleted('orders', ['id' => $order->id]);

        $archivedPage = $this->actingAs($salesUser)->get('/orders-archived');
        $archivedPage->assertStatus(200);
        $archivedPage->assertSee('INV-9999');

        $restoreResponse = $this->actingAs($salesUser)->post('/orders-archived/' . $order->id . '/restore');
        $restoreResponse->assertRedirect('/orders-archived');
        $order->refresh();
        $this->assertFalse($order->trashed());
    }
}
