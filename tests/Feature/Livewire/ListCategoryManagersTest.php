<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ListCategoryManagers;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListCategoryManagersTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function list_category_managers_component_can_render()
    {
        $category = Category::factory()->create();

        $component = Livewire::test(ListCategoryManagers::class, [
            'category' => $category->id,
        ]);

        $component->assertStatus(200);
    }

    #[Test]
    public function categories_show_page_contains_list_category_managers_component(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this
            ->actingAs($admin)
            ->get(route('categories.show', $category))
            ->assertSeeLivewire(ListCategoryManagers::class);
    }

    #[Test]
    public function list_category_managers_component_can_list_users(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $category = Category::factory()->create();
        $category->users()->attach($user1);
        $category->users()->attach($user2);
        $category->users()->attach($user3);

        $this->assertCount(4, User::all());
        $this->assertCount(1, Category::all());
        Livewire::actingAs($admin)
            ->test(ListCategoryManagers::class, [
                'category' => $category->id,
            ])
            ->assertSee([
                $user1->name,
                $user2->name,
                $user3->name,
            ]);
    }

    #[Test]
    public function list_category_managers_component_can_delete_a_manager(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $category->users()->attach($user);

        $this->assertCount(2, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(1, $category->users()->get());
        Livewire::actingAs($admin)
            ->test(ListCategoryManagers::class, [
                'category' => $category->id,
            ])
            ->call('deleteManager', $user->id);
        $this->assertCount(0, $category->users()->get());
    }
}
