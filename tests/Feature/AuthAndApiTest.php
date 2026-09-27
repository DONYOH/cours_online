<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_a_student(): void
    {
        $this->post(route('register'), [
            'name' => 'Yao Koffi',
            'email' => 'yao@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])->assertRedirect(route('catalog'));

        $this->assertAuthenticated();
        $this->assertSame('student', User::first()->role->value);
    }

    public function test_login_and_logout(): void
    {
        $user = User::factory()->create(['email' => 'a@b.fr']);

        $this->post(route('login'), ['email' => 'a@b.fr', 'password' => 'bad'])->assertSessionHasErrors('email');
        $this->post(route('login'), ['email' => 'a@b.fr', 'password' => 'password'])->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_api_token_flow(): void
    {
        $user = User::factory()->create(['email' => 'api@b.fr']);
        $course = Course::factory()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'progress' => 40]);

        $token = $this->postJson('/api/v1/token', ['email' => 'api@b.fr', 'password' => 'password', 'device_name' => 'mobile'])
            ->assertOk()->json('token');

        $headers = ['Authorization' => 'Bearer '.$token];
        $this->getJson('/api/v1/me', $headers)->assertOk()->assertJsonPath('email', 'api@b.fr');
        $this->getJson('/api/v1/me/courses', $headers)->assertOk()->assertJsonPath('data.0.progress', 40);
        $this->getJson('/api/v1/courses/'.$course->slug, $headers)->assertOk()->assertJsonPath('title', $course->title);
        $this->getJson('/api/v1/courses')->assertOk()->assertJsonPath('data.0.slug', $course->slug);
    }

    public function test_api_rejects_bad_credentials_and_unauthenticated_calls(): void
    {
        User::factory()->create(['email' => 'api@b.fr']);
        $this->postJson('/api/v1/token', ['email' => 'api@b.fr', 'password' => 'nope', 'device_name' => 'x'])->assertUnprocessable();
        $this->getJson('/api/v1/me')->assertUnauthorized();
    }
}
