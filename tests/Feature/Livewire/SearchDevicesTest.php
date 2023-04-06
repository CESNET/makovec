<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\SearchDevices;
use App\Models\Category;
use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SearchDevicesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function search_devices_component_can_render()
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);

        $component = Livewire::actingAs($admin)
            ->test(SearchDevices::class);

        $component->assertStatus(200);
    }

    /** @test */
    public function devices_index_page_contains_search_devices_component(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();
        Device::factory(10)->for($category)->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(10, Device::all());
        $this
            ->actingAs($admin)
            ->get(route('devices.index'))
            ->assertSeeLivewire(SearchDevices::class);
    }

    /** @test */
    public function search_devices_component_can_search_devices(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();
        $device = Device::factory()->for($category)->create();
        Device::factory(100)->for($category)->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(101, Device::all());

        Livewire::actingAs($admin)
            ->test(SearchDevices::class)
            ->set('search', $device->mac)
            ->assertSet('search', $device->mac)
            ->assertSeeInOrder([
                $device->mac,
                $device->category->type,
                $device->name,
            ]);
    }
}
