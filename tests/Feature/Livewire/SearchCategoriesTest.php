<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\SearchCategories;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class SearchCategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function search_categories_component_can_render()
    {
        $component = Livewire::test(SearchCategories::class);

        $component->assertStatus(200);
    }

    /** @test */
    public function categories_index_page_contains_search_categories_component(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        Category::factory()->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this
            ->actingAs($admin)
            ->get(route('categories.index'))
            ->assertSeeLivewire(SearchCategories::class);
    }

    /** @test */
    public function search_categories_component_can_search_categories(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();
        Category::factory(5)->create();

        $this->assertCount(1, User::all());
        $this->assertCount(6, Category::all());

        Livewire::withQueryParams(['search' => $category->type])
            ->actingAs($admin)
            ->test(SearchCategories::class)
            ->assertSet('search', $category->type)
            ->assertSeeInOrder([
                $category->type,
                Str::limit($category->description, 40),
                $category->vlan,
            ]);
    }
}
