<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlackjackTest extends TestCase
{
    public function test_redirect_to_login_when_unauthenticated(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_blackjack_page_is_displayed_when_authenticated(): void
    {
        $response = $this->actingAs($user = User::factory()->create())
            ->get('/');

        $response->assertOk();
    }
}
