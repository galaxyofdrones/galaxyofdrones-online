<?php

namespace Tests\Unit\Support;

use App\Models\Setting;
use App\Support\SettingManager;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SettingManagerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var SettingManager
     */
    protected $settingManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->settingManager = app(SettingManager::class);
    }

    /**
     * @throws \Exception|\Throwable|\Psr\SimpleCache\InvalidArgumentException
     */
    public function testAll()
    {
        Setting::factory(2)->create();

        $this->assertCount(2, $this->settingManager->all());
        $this->assertTrue(cache()->has(SettingManager::CACHE_KEY));
    }

    public function testValue()
    {
        Setting::factory()->create([
            'key' => 'title',
            'value' => [
                'en' => 'TestTitle',
            ],
        ]);

        Setting::factory()->create([
            'key' => 'color',
            'value' => [
                'en' => 'Color',
                'en_GB' => 'Colour',
            ],
        ]);

        $this->assertEquals('TestTitle', $this->settingManager->value('title'));
        $this->assertEquals('TestTitle', $this->settingManager->value('title', 'en_GB'));
        $this->assertNull($this->settingManager->value('title', 'en_GB', false));
        $this->assertEquals('Colour', $this->settingManager->value('color', 'en_GB'));
    }

    /**
     * @throws \Exception|\Throwable|\Psr\SimpleCache\InvalidArgumentException
     */
    public function testForget()
    {
        Setting::factory(2)->create();

        $this->assertCount(2, $this->settingManager->all());
        $this->assertTrue(cache()->has(SettingManager::CACHE_KEY));

        $this->settingManager->forget();

        $this->assertFalse(cache()->has(SettingManager::CACHE_KEY));
    }
}
