<?php

namespace Tests\Feature\Api;

use App\Models\Rank;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RankTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([
            'started_at' => Carbon::now(),
        ]);

        Passport::actingAs($user);
    }

    public function testPve()
    {
        $user = auth()->user();

        $rank = Rank::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->getJson('/api/rank/pve')->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'username',
                        'experience',
                        'mission_count',
                        'expedition_count',
                        'planet_count',
                        'winning_battle_count',
                        'losing_battle_count',
                    ],
                ],
            ])->assertJson([
                'data' => [
                    [
                        'id' => $rank->id,
                        'username' => $user->username,
                        'experience' => $user->experience,
                        'mission_count' => $rank->mission_count,
                        'expedition_count' => $rank->expedition_count,
                        'planet_count' => $rank->planet_count,
                        'winning_battle_count' => $rank->winning_battle_count,
                        'losing_battle_count' => $rank->losing_battle_count,
                    ],
                ],
            ]);
    }

    public function testPvp()
    {
        $user = auth()->user();

        $rank = Rank::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->getJson('/api/rank/pvp')->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'username',
                        'experience',
                        'mission_count',
                        'expedition_count',
                        'planet_count',
                        'winning_battle_count',
                        'losing_battle_count',
                    ],
                ],
            ])->assertJson([
                'data' => [
                    [
                        'id' => $rank->id,
                        'username' => $user->username,
                        'experience' => $user->experience,
                        'mission_count' => $rank->mission_count,
                        'expedition_count' => $rank->expedition_count,
                        'planet_count' => $rank->planet_count,
                        'winning_battle_count' => $rank->winning_battle_count,
                        'losing_battle_count' => $rank->losing_battle_count,
                    ],
                ],
            ]);
    }
}
