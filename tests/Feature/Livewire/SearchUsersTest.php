<?php

namespace Tests\Feature\Livewire;

use App\Livewire\SearchUsers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SearchUsersTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function search_users_component_can_render(): void
    {
        $component = Livewire::test(SearchUsers::class);

        $component->assertStatus(200);
    }

    #[Test]
    public function users_index_page_contains_search_users_component(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        User::factory(100)->create();

        $this->assertCount(101, User::all());
        $this
            ->actingAs($admin)
            ->get(route('users.index'))
            ->assertSeeLivewire(SearchUsers::class);
    }

    #[Test]
    public function search_users_component_can_search_users(): void
    {
        $alice = User::factory()->create(['active' => true, 'admin' => true]);
        $bob = User::factory()->create(['active' => true]);

        $this->assertCount(2, User::all());

        Livewire::test(SearchUsers::class)
            ->set('search', $alice->name)
            ->assertSet('search', $alice->name)
            ->assertSee($alice->name)
            ->assertDontSee($bob->name);
    }
}
