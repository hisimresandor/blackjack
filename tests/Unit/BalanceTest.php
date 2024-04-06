<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class BalanceTest extends TestCase
{
    public function test_redirect_to_login_when_unauthenticated(): void
    {
        $response = $this->get('/balance');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_balance_page_is_displayed_when_authenticated(): void
    {
        $response = $this->actingAs($user = User::factory()->create())
            ->get('/balance');

        $response->assertOk();
    }

    public function test_deposit_amount_is_added_to_balance(): void
    {
        $user = User::factory()->create();

        $request = new Request([
            'amount' => 1000,
        ]);

        $response = $this->actingAs($user)->post('/balance/deposit', $request->all());

        $response
            ->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertSame(1000, $user->balance);
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => 1000,
            'balance_before' => 0,
            'balance_after' => 1000,
        ]);
    }

    public function test_withdraw_amount_is_subtracted_from_balance(): void
    {
        $user = User::factory()->create([
            'balance' => 1000,
        ]);

        $request = new Request([
            'amount' => 500,
        ]);

        $response = $this->actingAs($user)->post('/balance/withdraw', $request->all());

        $response
            ->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertSame(500, $user->balance);
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type' => 'withdraw',
            'amount' => 500,
            'balance_before' => 1000,
            'balance_after' => 500,
        ]);
    }
}
