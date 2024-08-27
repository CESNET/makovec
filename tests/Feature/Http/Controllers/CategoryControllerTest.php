<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_anonymouse_cannot_see_the_list_of_categories(): void
    {
        Category::factory()->times(5)->create();

        $this->assertCount(5, Category::all());
        $this
            ->followingRedirects()
            ->get(route('categories.index'))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function an_anonymouse_cannot_see_a_form_to_add_a_new_category(): void
    {
        $this
            ->followingRedirects()
            ->get(route('categories.create'))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function an_anonymouse_cannot_add_a_new_category(): void
    {
        $this
            ->followingRedirects()
            ->post(route('categories.store'))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function an_anonymouse_cannot_see_categories_details(): void
    {
        $category = Category::factory()->create();

        $this
            ->followingRedirects()
            ->get(route('categories.show', $category))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function an_anonymouse_cannot_see_the_edit_form_for_a_category(): void
    {
        $category = Category::factory()->create();

        $this
            ->followingRedirects()
            ->get(route('categories.edit', $category))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function an_anonymouse_cannot_update_a_category(): void
    {
        $category = Category::factory()->create();

        $this
            ->followingRedirects()
            ->patch(route('categories.update', $category))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function an_anonymouse_cannot_delete_a_category(): void
    {
        $category = Category::factory()->create();

        $this
            ->followingRedirects()
            ->delete(route('categories.destroy', $category))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function a_user_cannot_see_the_list_of_categories(): void
    {
        $user = User::factory()->create(['active' => true]);
        Category::factory()->times(5)->create();

        $this->assertCount(5, Category::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('categories.index'))
            ->assertForbidden();
    }

    #[Test]
    public function a_user_cannot_see_a_form_to_add_a_new_category(): void
    {
        $user = User::factory()->create(['active' => true]);

        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('categories.create'))
            ->assertForbidden();
    }

    #[Test]
    public function a_user_cannot_add_a_new_category(): void
    {
        $user = User::factory()->create(['active' => true]);
        $category = Category::factory()->make()->toArray();

        $this
            ->actingAs($user)
            ->followingRedirects()
            ->post(route('categories.store', $category))
            ->assertForbidden();
    }

    #[Test]
    public function a_user_cannot_see_categories_details(): void
    {
        $user = User::factory()->create(['active' => true]);
        $category = Category::factory()->create();

        $this->assertCount(1, Category::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('categories.show', $category))
            ->assertForbidden();
    }

    #[Test]
    public function a_user_cannot_see_the_edit_form_for_a_category(): void
    {
        $user = User::factory()->create(['active' => true]);
        $category = Category::factory()->create();

        $this->assertCount(1, Category::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('categories.edit', $category))
            ->assertForbidden();
    }

    #[Test]
    public function a_user_cannot_update_a_category(): void
    {
        $user = User::factory()->create(['active' => true]);
        $category = Category::factory()->create();
        $category_new = Category::factory()->make()->toArray();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('categories.update', $category), $category_new)
            ->assertForbidden();
    }

    #[Test]
    public function a_user_cannot_delete_a_category(): void
    {
        $user = User::factory()->create(['active' => true]);
        $category = Category::factory()->create();

        $this->assertCount(1, Category::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->delete(route('categories.destroy', $category))
            ->assertForbidden();
    }

    #[Test]
    public function an_admin_can_see_the_list_of_categories(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        Category::factory()->times(5)->create();

        $this->assertCount(1, User::all());
        $this->assertCount(5, Category::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->get(route('categories.index'))
            ->assertOk()
            ->assertSeeTextInOrder(Category::pluck('type')->toArray());
    }

    #[Test]
    public function an_admin_can_see_a_form_to_add_a_new_category(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);

        $this->assertCount(1, User::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->get(route('categories.create'))
            ->assertOk();
    }

    #[Test]
    public function an_admin_can_add_a_new_category(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->make()->toArray();

        $this->assertCount(1, User::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->post(route('categories.store', $category))
            ->assertOk()
            ->assertSeeText(__('categories.added', ['type' => $category['type']]));
    }

    #[DataProvider('invalidCategoryData')]
    public function test_validation_for_a_new_category(string $field, mixed $data, string $message): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);

        $this->assertCount(1, User::all());
        $this
            ->actingAs($admin)
            ->post(route('categories.store', [$field => $data]))
            ->assertSessionHasErrors([$field => $message]);
    }

    public static function invalidCategoryData(): array
    {
        return [
            ['type', '', 'The type field is required.'],
            ['type', '@@@', 'The type field must only contain letters.'],
            ['type', 'A', 'The type field must be at least 2 characters.'],
            ['type', str_repeat('x', 33), 'The type field must not be greater than 32 characters.'],
            ['description', '', 'The description field is required.'],
            ['description', str_repeat('x', 256), 'The description field must not be greater than 255 characters.'],
            ['vlan', '', 'The vlan field is required.'],
            ['vlan', '@', 'The vlan field format is invalid.'],
            ['vlan', str_repeat('x', 65), 'The vlan field must not be greater than 64 characters.'],
        ];
    }

    #[Test]
    public function an_admin_cannot_add_a_category_with_duplicated_vlan(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();
        $category_new = Category::factory()->make(['vlan' => $category->vlan])->toArray();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this
            ->actingAs($admin)
            ->post(route('categories.store', $category_new))
            ->assertSessionHasErrors(['vlan' => __('categories.uniqueness_required')]);
    }

    #[Test]
    public function an_admin_can_see_categories_details(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->get(route('categories.show', $category))
            ->assertOk()
            ->assertSeeInOrder(Category::select('type', 'description', 'vlan')->first()->toArray());
    }

    #[Test]
    public function an_admin_can_see_the_edit_form_for_a_category(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->get(route('categories.edit', $category))
            ->assertOk()
            ->assertSeeInOrder(Category::select('type', 'description', 'vlan')->first()->toArray());
    }

    #[Test]
    public function an_admin_can_update_a_category(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();
        $category_new = Category::factory()->make()->toArray();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('categories.update', $category), $category_new)
            ->assertOk()
            ->assertSeeText(__('categories.updated', ['type' => $category_new['type']]));
    }

    #[DataProvider('invalidCategoryData')]
    public function test_validation_for_an_existing_category(string $field, mixed $data, string $message): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this
            ->actingAs($admin)
            ->patch(route('categories.update', $category), [$field => $data])
            ->assertSessionHasErrors([$field => $message]);
    }

    #[Test]
    public function an_admin_can_update_a_category_with_no_change(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('categories.update', $category), $category->toArray())
            ->assertOk();
    }

    #[Test]
    public function an_admin_cannot_update_a_category_with_duplicated_vlan(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category_old = Category::factory()->create();
        $category = Category::factory()->create();

        $this->assertCount(1, User::all());
        $this->assertCount(2, Category::all());
        $this
            ->actingAs($admin)
            ->patch(route('categories.update', $category), $category_old->toArray())
            ->assertSessionHasErrors(['vlan' => __('categories.uniqueness_required')]);
    }

    #[Test]
    public function an_admin_can_delete_a_category(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->delete(route('categories.destroy', $category))
            ->assertOk()
            ->assertSeeText(__('categories.deleted', ['type' => $category->type]));
        $this->assertCount(0, Category::all());
    }

    #[Test]
    public function an_admin_cannot_delete_a_category_with_devices(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();
        $device = Device::factory()->for($category)->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(1, Device::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->delete(route('categories.destroy', $category))
            ->assertOk()
            ->assertSeeText(__('categories.deleting_category_with_devices_forbidden'));
        $this->assertCount(1, Category::all());
    }

    #[Test]
    public function a_form_to_add_a_category_shows_all_texts(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);

        $this->assertCount(1, User::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->get(route('categories.create'))
            ->assertOk()
            ->assertSeeInOrder([
                __('categories.add'),
                __('categories.profile'),
                __('common.type'),
                __('inputs.placeholder_type'),
                __('common.description'),
                __('inputs.placeholder_description'),
                __('common.vlan'),
                __('inputs.placeholder_vlan'),
                __('categories.vlan_regexp'),
                __('common.back'),
                __('common.add'),
            ]);
    }
}
