<?php

namespace Tests\Feature\Api;

use App\Models\Building;
use App\Models\Grid;
use App\Models\Planet;
use App\Models\Training;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TrainerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([
            'started_at' => Carbon::now(),
            'energy' => 1000,
        ]);

        $planet = Planet::factory()->create([
            'user_id' => $user->id,
            'x' => 12,
            'y' => 17,
        ]);

        Passport::actingAs($user);

        $user->update([
            'capital_id' => $planet->id,
        ]);
    }

    public function testIndex()
    {
        $planet = Planet::factory()->create([
            'user_id' => auth()->user()->id,
            'x' => 1,
            'y' => 1,
        ]);

        $building = Building::factory()->create([
            'type' => Building::TYPE_TRAINER,
            'train_time_bonus' => 5,
            'end_level' => 1,
        ]);

        $grid = Grid::factory()->create([
            'building_id' => $building->id,
            'planet_id' => $planet->id,
        ]);

        $unit = Unit::factory()->create([
            'train_time' => 10,
        ]);

        $training = Training::factory()->create([
            'grid_id' => $grid->id,
            'unit_id' => $unit->id,
        ]);

        $this->getJson("/api/trainer/{$grid->id}")->assertStatus(200)
            ->assertJsonStructure([
                'remaining',
                'quantity',
                'units' => [
                    [
                        'id',
                        'name',
                        'type',
                        'speed',
                        'attack',
                        'defense',
                        'supply',
                        'train_cost',
                        'train_time',
                        'description',
                        'detection',
                        'capacity',
                        'research_experience',
                        'research_cost',
                        'research_time',
                    ],
                ],
            ])->assertJson([
                'remaining' => $training->remaining,
                'quantity' => $training->quantity,
                'units' => [
                    [
                        'id' => $unit->id,
                        'name' => $unit->translation('name'),
                        'type' => $unit->type,
                        'speed' => $unit->speed,
                        'attack' => $unit->attack,
                        'defense' => $unit->defense,
                        'supply' => $unit->supply,
                        'train_cost' => $unit->train_cost,
                        'train_time' => 0,
                        'description' => $unit->translation('description'),
                        'detection' => $unit->detection,
                        'capacity' => $unit->capacity,
                        'research_experience' => $unit->research_experience,
                        'research_cost' => $unit->research_cost,
                        'research_time' => $unit->research_time,
                    ],
                ],
            ]);
    }

    public function testStore()
    {
        $user = auth()->user();

        $planet = Planet::factory()->create([
            'user_id' => null,
            'supply' => 500,
            'x' => 2,
            'y' => 2,
        ]);

        $planet->update([
            'user_id' => $user->id,
        ]);

        $building = Building::factory()->create([
            'type' => Building::TYPE_TRAINER,
            'train_time_bonus' => 5,
            'end_level' => 1,
        ]);

        $grid = Grid::factory()->create([
            'building_id' => $building->id,
            'planet_id' => $planet->id,
        ]);

        $unit = Unit::factory()->create([
            'train_cost' => 10,
            'supply' => 10,
        ]);

        $training = Training::factory()->create([
            'grid_id' => $grid->id,
            'unit_id' => $unit->id,
        ]);

        $this->post('/api/trainer/10/10')
            ->assertStatus(404);

        $this->post('/api/trainer/not-id/not-id')
            ->assertStatus(404);

        $this->post("/api/trainer/{$grid->id}/{$unit->id}")
            ->assertStatus(400);

        $training->delete();

        $user->units()->attach($unit, [
            'is_researched' => true,
            'quantity' => 10,
        ]);

        $this->post("/api/trainer/{$grid->id}/{$unit->id}")
            ->assertStatus(400);

        for ($i = 1; $i < 10; ++$i) {
            $tmpPlanet = Planet::factory()->create([
                'user_id' => null,
                'x' => $user->capital->x + Planet::PENALTY_STEP + $i,
                'y' => $user->capital->y + Planet::PENALTY_STEP + $i,
            ]);

            $tmpPlanet->update([
                'user_id' => $user->id,
            ]);
        }

        $user->update([
            'energy' => 51,
        ]);

        $this->post("/api/trainer/{$grid->id}/{$unit->id}", [
            'quantity' => 5,
        ])->assertStatus(400);

        $user->update([
            'energy' => 100,
        ]);

        $this->post("/api/trainer/{$grid->id}/{$unit->id}", [
            'quantity' => 5,
        ])->assertStatus(200);
    }

    public function testDestroy()
    {
        $planet = Planet::factory()->create([
            'user_id' => auth()->user()->id,
            'x' => 3,
            'y' => 3,
        ]);

        $grid = Grid::factory()->create([
            'planet_id' => $planet->id,
        ]);

        Training::factory()->create([
            'grid_id' => $grid->id,
        ]);

        $this->delete('/api/trainer/10')
            ->assertStatus(404);

        $this->delete('/api/trainer/not-id')
            ->assertStatus(404);

        $this->assertDatabaseHas('trainings', [
            'grid_id' => $grid->id,
        ]);

        $this->delete("/api/trainer/{$grid->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('trainings', [
            'grid_id' => $grid->id,
        ]);
    }
}
