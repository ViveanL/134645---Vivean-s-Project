<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Models\Admin;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Mail\SendOtpMail;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_display_admin_index_page()
    {
        // Arrange
        $admin = Admin::factory()->create();

        // Act
        $response = $this->get(route('admin.index'));

        // Assert
        $response->assertStatus(200)
                 ->assertViewIs('admin.index')
                 ->assertViewHas('admin')
                 ->assertSee($admin->name); // Adjust this based on your actual view content
    }

    /** @test */
    public function it_can_display_create_admin_form()
    {
        // Act
        $response = $this->get(route('admin.create'));

        // Assert
        $response->assertStatus(200)
                 ->assertViewIs('admin.create');
    }

    /** @test */
    public function it_can_store_a_new_admin()
    {
        // Arrange
        Storage::fake('public');

        $adminData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
        ];

        // Act
        $response = $this->post(route('admin.store'), $adminData);

        // Assert
        $response->assertRedirect(route('admin.index'))
                 ->assertSessionHas('success', 'New Admin has been created!');

        $this->assertDatabaseHas('admins', [
            'name' => $adminData['name'],
            'email' => $adminData['email'],
            // Add more assertions as needed
        ]);
    }

    /** @test */
    public function it_can_display_edit_admin_form()
    {
        // Arrange
        $admin = Admin::factory()->create();

        // Act
        $response = $this->get(route('admin.edit', $admin));

        // Assert
        $response->assertStatus(200)
                 ->assertViewIs('admin.edit')
                 ->assertViewHas('user', $admin);
    }

    /** @test */
    public function it_can_update_admin_information()
    {
        // Arrange
        Storage::fake('public');

        $admin = Admin::factory()->create();

        $updatedAdminData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'newpassword123',
        ];

        // Act
        $response = $this->put(route('admin.update', $admin), $updatedAdminData);

        // Assert
        $response->assertRedirect(route('admin.index'))
                 ->assertSessionHas('success', 'Admin has been updated!');

        $this->assertDatabaseHas('admins', [
            'id' => $admin->id,
            'name' => $updatedAdminData['name'],
            'email' => $updatedAdminData['email'],
            // Add more assertions as needed
        ]);
    }

    /** @test */
    public function it_can_delete_admin()
    {
        // Arrange
        $admin = Admin::factory()->create();

        // Act
        $response = $this->delete(route('admin.destroy', $admin));

        // Assert
        $response->assertRedirect(route('admin.index'))
                 ->assertSessionHas('success', 'Admin has been deleted!');

        $this->assertDatabaseMissing('admins', ['id' => $admin->id]);
    }

    // Add more test methods for other controller actions as needed

    /** @test */
    public function it_can_resend_otp_for_admin()
    {
        // Arrange
        $admin = Admin::factory()->create();

        // Act
        $response = $this->post(route('admin.resendOtp', $admin));

        // Assert
        $response->assertRedirect('/verify-login-otp')
                 ->assertSessionHas('message', 'A new OTP has been sent to your email.');

        // Add more assertions if needed
    }

    /** @test */
    public function it_can_resend_registration_otp_for_admin()
    {
        // Arrange
        $admin = Admin::factory()->create();

        // Act
        $response = $this->post(route('admin.resendRegOtp', $admin));

        // Assert
        $response->assertRedirect('/verify-registration-otp')
                 ->assertSessionHas('message', 'A new OTP has been sent to your email.');

        // Add more assertions if needed
    }
}
