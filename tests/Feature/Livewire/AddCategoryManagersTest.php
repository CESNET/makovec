<?php

namespace Tests\Feature\Livewire;

use App\Livewire\AddCategoryManagers;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AddCategoryManagersTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function add_category_managers_component_can_render()
    {
        $component = Livewire::test(AddCategoryManagers::class);

        $component->assertStatus(200);
    }

    #[Test]
    public function add_category_managers_component_can_add_a_manager(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $this->assertCount(2, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(0, $category->users()->get());
        Livewire::actingAs($admin)
            ->test(AddCategoryManagers::class, [
                'category' => $category->id,
            ])
            ->call('addManager', $user->id)
            ->assertDispatched('manager-added');
        $this->assertCount(1, $category->users()->get());
    }

    #[Test]
    public function add_category_managers_component_can_search_users(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $user = User::factory()->create(['active' => true]);
        $category = Category::factory()->create();

        $this->assertCount(2, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(0, $category->users()->get());
        Livewire::actingAs($admin)
            ->test(AddCategoryManagers::class, [
                'category' => $category->id,
            ])
            ->set('search', $user->name)
            ->assertSet('search', $user->name)
            ->assertSee([
                $user->name,
                $user->email,
            ]);
    }
}
