<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_access_own_profile(): void
    {
        $memberUser = User::factory()->create([
            'role' => 'member',
        ]);

        $response = $this->actingAs($memberUser)
            ->get('/profile');

        $response->assertStatus(200);
    }

    public function test_member_cannot_access_book_management(): void
    {
        $memberUser = User::factory()->create([
            'role' => 'member',
        ]);

        $response = $this->actingAs($memberUser)
            ->get('/books');

        $response->assertStatus(403);
    }

    public function test_member_cannot_access_system_settings(): void
    {
        $memberUser = User::factory()->create([
            'role' => 'member',
        ]);

        $response = $this->actingAs($memberUser)
            ->get('/settings');

        $response->assertStatus(403);
    }

    public function test_librarian_can_access_book_management(): void
    {
        $librarian = User::factory()->create([
            'role' => 'librarian',
        ]);

        $response = $this->actingAs($librarian)
            ->get('/books');

        $response->assertStatus(200);
    }

    public function test_librarian_cannot_access_system_settings(): void
    {
        $librarian = User::factory()->create([
            'role' => 'librarian',
        ]);

        $response = $this->actingAs($librarian)
            ->get('/settings');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_system_settings(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->get('/settings');

        $response->assertStatus(200);
    }
}