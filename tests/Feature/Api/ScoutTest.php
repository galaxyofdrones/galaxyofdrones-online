<?php

namespace Tests\Feature\Api;

use App\Models\Building;
use App\Models\Grid;
use App\Models\Movement;
use App\Models\Planet;
use App\Models\Resource;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ScoutTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        Passport::actingAs($user);

        $planet = Planet::factory()->create([
            'user_id' => $user->id,
            'name' => 'Earth',
            'x' => 1,
            'y' => 1,
        ]);

        $user->update([
            'current_id' => $planet->id,
            'started_at' => Carbon::now(),
        ]);
    }

    public function testIndex()
    {
        $user = auth()->user();

        $building = Building::factory()->create([
            'type' => Building::TYPE_SCOUT,
        ]);

        $planet = Planet::factory()->create([
            'user_id' => $user->id,
            'x' => 2,
            'y' => 2,
        ]);

        $grid = Grid::factory()->create([
            'planet_id' => $planet->id,
            'building_id' => $building->id,
        ]);

        $unit = Unit::factory()->create();
        $resource = Resource::factory()->create();

        $movement = Movement::factory()->create([
            'start_id' => $user->current_id,
            'end_id' => $planet->id,
            'type' => Movement::TYPE_OCCUPY,
        ]);

        $movement->units()->attach($unit, [
            'quantity' => 10,
        ]);

        $movement->resources()->attach($resource, [
            'quantity' => 10,
        ]);

        $movement2 = Movement::factory()->create([
            'start_id' => $planet->id,
            'end_id' => $user->current_id,
            'user_id' => $user->id,
        ]);

        $movement2->units()->attach($unit, [
            'quantity' => 10,
        ]);

        $movement2->resources()->attach($resource, [
            'quantity' => 10,
        ]);

        $this->getJson('/api/scout/10')->assertStatus(404);

        $this->getJson('/api/scout/not-id')->assertStatus(404);

        $this->getJson("/api/scout/{$grid->id}")->assertStatus(200)
            ->assertJsonStructure([
                'incoming_movements' => [
                    [
                        'id',
                        'type',
                        'remaining',
                        'start' => [
                            'id',
                            'resource_id',
                            'display_name',
                            'x',
                            'y',
                        ],
                        'end' => [
                            'id',
                            'resource_id',
                            'display_name',
                            'x',
                            'y',
                        ],
                        'resources' => [
                            [
                                'id',
                                'name',
                                'description',
                                'quantity',
                            ],
                        ],
                        'units' => [
                            [
                                'id',
                                'name',
                                'description',
                                'quantity',
                            ],
                        ],
                    ],
                ],
                'outgoing_movements' => [
                    [
                        'id',
                        'type',
                        'remaining',
                        'start' => [
                            'id',
                            'resource_id',
                            'display_name',
                            'x',
                            'y',
                        ],
                        'end' => [
                            'id',
                            'resource_id',
                            'display_name',
                            'x',
                            'y',
                        ],
                        'resources' => [
                            [
                                'id',
                                'name',
                                'description',
                                'quantity',
                            ],
                        ],
                        'units' => [
                            [
                                'id',
                                'name',
                                'description',
                                'quantity',
                            ],
                        ],
                    ],
                ],
            ])->assertJson([
                'incoming_movements' => [
                    [
                        'id' => $movement->id,
                        'type' => $movement->type,
                        'remaining' => $movement->remaining,
                        'start' => [
                            'id' => $user->current->id,
                            'resource_id' => $user->current->resource_id,
                            'display_name' => $user->current->display_name,
                            'x' => $user->current->x,
                            'y' => $user->current->y,
                        ],
                        'end' => [
                            'id' => $planet->id,
                            'resource_id' => $planet->resource_id,
                            'display_name' => $planet->display_name,
                            'x' => $planet->x,
                            'y' => $planet->y,
                        ],
                        'resources' => [
                            [
                                'id' => $resource->id,
                                'name' => $resource->translation('name'),
                                'description' => $resource->translation('description'),
                                'quantity' => 10,
                            ],
                        ],
                        'units' => [
                            [
                                'id' => $unit->id,
                                'name' => $unit->translation('name'),
                                'description' => $unit->translation('description'),
                                'quantity' => 10,
                            ],
                        ],
                    ],
                ],
                'outgoing_movements' => [
                    [
                        'id' => $movement2->id,
                        'type' => $movement2->type,
                        'remaining' => $movement2->remaining,
                        'start' => [
                            'id' => $planet->id,
                            'resource_id' => $planet->resource_id,
                            'display_name' => $planet->display_name,
                            'x' => $planet->x,
                            'y' => $planet->y,
                        ],
                        'end' => [
                            'id' => $user->current->id,
                            'resource_id' => $user->current->resource_id,
                            'display_name' => $user->current->display_name,
                            'x' => $user->current->x,
                            'y' => $user->current->y,
                        ],
                        'resources' => [
                            [
                                'id' => $resource->id,
                                'name' => $resource->translation('name'),
                                'description' => $resource->translation('description'),
                                'quantity' => 10,
                            ],
                        ],
                        'units' => [
                            [
                                'id' => $unit->id,
                                'name' => $unit->translation('name'),
                                'description' => $unit->translation('description'),
                                'quantity' => 10,
                            ],
                        ],
                    ],
                ],
            ]);
    }
}
