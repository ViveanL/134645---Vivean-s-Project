<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // ... Other methods ...

    /** @test */
    public function it_displays_users_index()
    {
        $response = $this->get(route('users.index'));

        $response->assertStatus(200)
            ->assertViewIs('users.index')
            ->assertViewHas('users');
    }

    /** @test */
    public function it_displays_create_user_form()
    {
        $response = $this->get(route('users.create'));

        $response->assertStatus(200)
            ->assertViewIs('users.create');
    }

    /** @test */
    public function it_creates_new_user()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password,
            // Add other required fields here
        ];

        $response = $this->post(route('users.store'), $data);

        $response->assertRedirect(route('users.index'))
            ->assertSessionHas('success', 'New User has been created!');
    }

    /** @test */
    public function it_displays_edit_user_form()
    {
        $user = User::factory()->create();
        $response = $this->get(route('users.edit', $user));

        $response->assertStatus(200)
            ->assertViewIs('users.edit')
            ->assertViewHas('user');
    }

    /** @test */
    public function it_updates_user()
    {
        $user = User::factory()->create();

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            // Add other fields to update
        ];

        $response = $this->put(route('users.update', $user), $data);

        $response->assertRedirect(route('users.index'))
            ->assertSessionHas('success', 'User has been updated!');
    }

    /** @test */
    public function it_deletes_user()
    {
        $user = User::factory()->create();

        $response = $this->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'))
            ->assertSessionHas('success', 'User has been deleted!');
    }

    /** @test */
    public function it_displays_login_form()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200)
            ->assertViewIs('auth.login');
    }

    // Add more tests for registration, authentication, and OTP verification processes here
}
