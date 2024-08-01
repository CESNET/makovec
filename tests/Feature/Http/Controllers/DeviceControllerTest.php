<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeviceControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_anonymouse_cannot_see_the_list_of_devices(): void
    {
        Device::factory()->for(Category::factory())->count(10)->create();

        $this->assertCount(10, Device::all());
        $this
            ->followingRedirects()
            ->get(route('devices.index'))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function an_anonymouse_cannot_see_a_form_to_add_a_new_device(): void
    {
        $this
            ->followingRedirects()
            ->get(route('devices.create'))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function an_anonymouse_cannot_add_a_new_device(): void
    {
        $device = Device::factory()->make()->toArray();

        $this
            ->followingRedirects()
            ->post(route('devices.store', $device))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function an_anonymouse_cannot_see_devices_details(): void
    {
        $device = Device::factory()->for(Category::factory())->create();

        $this->assertCount(1, Device::all());
        $this
            ->followingRedirects()
            ->get(route('devices.show', $device))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function an_anonymouse_cannot_see_devices_edit_form(): void
    {
        $device = Device::factory()->for(Category::factory())->create();

        $this->assertCount(1, Device::all());
        $this
            ->followingRedirects()
            ->get(route('devices.edit', $device))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function an_anonymouse_cannot_update_a_device(): void
    {
        $device = Device::factory()->for(Category::factory())->create();

        $this->assertCount(1, Device::all());
        $this
            ->followingRedirects()
            ->patch(route('devices.update', $device))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function an_anonymouse_cannot_delete_a_device(): void
    {
        $device = Device::factory()->for(Category::factory())->create();

        $this->assertCount(1, Device::all());
        $this
            ->followingRedirects()
            ->delete(route('devices.destroy', $device))
            ->assertOk()
            ->assertSeeText('login');
    }

    #[Test]
    public function a_user_without_permissions_cannot_see_the_list_of_devices(): void
    {
        $user = User::factory()->create(['active' => true]);
        Device::factory()->for(Category::factory())->count(10)->create();

        $this->assertCount(1, User::all());
        $this->assertCount(10, Device::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('devices.index'))
            ->assertForbidden();
    }

    #[Test]
    public function a_user_without_permissions_cannot_see_a_form_to_add_a_new_device(): void
    {
        $user = User::factory()->create(['active' => true]);

        $this->assertCount(1, User::all());
        $this
            ->actingAs($user)
            ->get(route('devices.create'))
            ->assertForbidden();
    }

    #[Test]
    public function a_user_without_permissions_cannot_add_a_new_device(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->refresh();
        $category = Category::factory()->create();
        $device = Device::factory()->for($category)->make(['type' => $category->type])->toArray();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this
            ->actingAs($user)
            ->post(route('devices.store', $device))
            ->assertSessionHasErrors(['category_id' => __('devices.no_permissions_for_this_category')]);
        $this->assertCount(0, Device::all());
    }

    #[Test]
    public function a_user_without_permissions_cannot_see_devices_details(): void
    {
        $user = User::factory()->create(['active' => true]);
        $device = Device::factory()->for(Category::factory())->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Device::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('devices.show', $device))
            ->assertForbidden();
    }

    #[Test]
    public function a_user_without_permissions_cannot_see_devices_edit_form(): void
    {
        $user = User::factory()->create(['active' => true]);
        $device = Device::factory()->for(Category::factory())->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Device::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('devices.edit', $device))
            ->assertForbidden();
    }

    #[Test]
    public function a_user_without_permissions_cannot_update_a_device(): void
    {
        $user = User::factory()->create(['active' => true]);
        $device = Device::factory()->for(Category::factory())->create();
        $device_new = Device::factory()->make()->toArray();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Device::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('devices.update', $device), $device_new)
            ->assertForbidden();
    }

    #[Test]
    public function a_user_without_permissions_cannot_delete_a_device(): void
    {
        $user = User::factory()->create(['active' => true]);
        $device = Device::factory()->for(Category::factory())->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Device::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->delete(route('devices.destroy', $device))
            ->assertForbidden();
    }

    #[Test]
    public function a_user_with_permissions_can_see_the_list_of_devices(): void
    {
        $user = User::factory()->create(['active' => true]);
        $category = Category::factory()->create();
        $user->categories()->attach($category);
        $user->refresh();
        Device::factory()->times(10)->for($category)->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(10, Device::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('devices.index'))
            ->assertOk()
            ->assertSeeTextInOrder(Device::orderBy('mac')->select('mac')->get()->pluck('mac')->toArray());
    }

    #[Test]
    public function a_user_with_permissions_can_see_a_form_to_add_a_new_device(): void
    {
        $user = User::factory()->create(['active' => true]);
        $category = Category::factory()->create();
        $user->categories()->attach($category);
        $user->refresh();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('devices.create'))
            ->assertOk()
            ->assertSeeTextInOrder([
                __('common.category'),
                __('common.mac'),
                __('common.name'),
                __('common.description'),
                __('common.enabled'),
                __('common.valid_from'),
                __('common.valid_to'),
            ]);
    }

    #[Test]
    public function a_user_with_permissions_can_add_a_new_device(): void
    {
        $user = User::factory()->create(['active' => true]);
        $category = Category::factory()->create();
        $user->categories()->attach($category);
        $user->refresh();
        $device = Device::factory()->for($category)->make()->toArray();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->post(route('devices.store', $device))
            ->assertOk()
            ->assertSeeText(__('devices.added', ['name' => $device['mac'], 'category' => $category->description]));
        $this->assertCount(1, Device::all());
    }

    #[Test]
    public function a_user_with_permissions_can_see_devices_details(): void
    {
        $user = User::factory()->create(['active' => true]);
        $category = Category::factory()->create();
        $user->categories()->attach($category);
        $user->refresh();
        $device = Device::factory()->for($category)->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(1, Device::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('devices.show', $device))
            ->assertOk()
            ->assertSeeTextInOrder([$device->mac, $device->name, $device->description]);
    }

    #[Test]
    public function a_user_with_permissions_can_see_devices_edit_form(): void
    {
        $user = User::factory()->create(['active' => true]);
        $category = Category::factory()->create();
        $user->categories()->attach($category);
        $user->refresh();
        $device = Device::factory()->for($category)->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(1, Device::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->get(route('devices.edit', $device))
            ->assertOk()
            ->assertSeeInOrder([$device->mac, $device->name, $device->description ?? '--']);
    }

    #[Test]
    public function a_user_with_permissions_can_update_a_device(): void
    {
        $user = User::factory()->create(['active' => true]);
        $category = Category::factory()->create();
        $user->categories()->attach($category);
        $user->refresh();
        $device = Device::factory()->for($category)->create();
        $device_new = Device::factory()->for($category)->make(['mac' => '01:02:03:04:05:06'])->toArray();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(1, Device::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->patch(route('devices.update', $device), $device_new)
            ->assertOk()
            ->assertSeeText(__('devices.updated', ['name' => $device_new['mac'], 'model' => $category->type]));
    }

    #[Test]
    public function a_user_with_permissions_can_delete_a_device(): void
    {
        $user = User::factory()->create(['active' => true]);
        $category = Category::factory()->create();
        $user->categories()->attach($category);
        $user->refresh();
        $device = Device::factory()->for($category)->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(1, Device::all());
        $this
            ->actingAs($user)
            ->followingRedirects()
            ->delete(route('devices.destroy', $device))
            ->assertOk()
            ->assertSeeText(__('devices.deleted', ['name' => $device->mac, 'model' => $device->type]));
    }

    #[Test]
    public function an_admin_can_see_the_list_of_devices(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();
        $devices = Device::factory()->times(10)->for($category)->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(10, Device::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->get(route('devices.index'))
            ->assertOk()
            ->assertSeeTextInOrder(Device::orderBy('mac')->select('mac')->get()->pluck('mac')->toArray());
    }

    #[Test]
    public function an_admin_can_see_a_form_to_add_a_new_device(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);

        $this->assertCount(1, User::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->get(route('devices.create'))
            ->assertOk()
            ->assertSeeTextInOrder([
                __('common.category'),
                __('common.mac'),
                __('common.name'),
                __('common.description'),
                __('common.enabled'),
                __('common.valid_from'),
                __('common.valid_to'),
            ]);
    }

    #[Test]
    public function an_admin_can_add_a_new_device(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();
        $device = Device::factory()->for($category)->make()->toArray();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->post(route('devices.store', $device))
            ->assertOk()
            ->assertSeeText(__('devices.added', ['name' => $device['mac'], 'category' => $category->description]));
        $this->assertCount(1, Device::all());
    }

    #[DataProvider('invalidDeviceData')]
    public function test_validation_for_a_new_device(string $field, mixed $data, string $message): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);

        $this->assertCount(1, User::all());
        $this->assertCount(0, Device::all());
        $this
            ->actingAs($admin)
            ->post(route('devices.store', [$field => $data]))
            ->assertSessionHasErrors([$field => $message]);
    }

    public static function invalidDeviceData(): array
    {
        return [
            ['mac', '', 'The mac field is required.'],
            ['mac', fake()->word(), 'The mac field must be a valid MAC address.'],
            ['category_id', '', 'The category id field is required.'],
            ['name', 'A', 'The name field must be at least 3 characters.'],
            ['name', str_repeat('x', 65), 'The name field must not be greater than 64 characters.'],
            ['description', str_repeat('x', 256), 'The description field must not be greater than 255 characters.'],
            ['enabled', '', 'The enabled field is required.'],
            ['enabled', 'A', 'The enabled field must be true or false.'],
            ['valid_from', fake()->word(), 'The valid from field must be a valid date.'],
            ['valid_to', fake()->word(), 'The valid to field must be a valid date.'],
        ];
    }

    #[Test]
    public function an_admin_cannot_add_a_device_with_reserved_mac_address(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();
        $device = Device::factory()->for($category)->make(['mac' => '01:00:5e:00:00:00'])->toArray();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this
            ->actingAs($admin)
            ->post(route('devices.store', $device))
            ->assertStatus(302)
            ->assertSessionHasErrors(['mac' => __('devices.reserved_mac_address')]);
        $this->assertCount(0, Device::all());
    }

    #[Test]
    public function an_admin_cannot_add_a_device_with_duplicated_mac_address(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();
        $device = Device::factory()->for($category)->create();
        $device_new = Device::factory()->make(['mac' => $device->mac])->toArray();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(1, Device::all());
        $this
            ->actingAs($admin)
            ->post(route('devices.store', $device_new))
            ->assertStatus(302)
            ->assertSessionHasErrors(['mac' => __('devices.uniqueness_required')]);
        $this->assertCount(1, Device::all());
    }

    #[Test]
    public function an_admin_cannot_add_a_device_to_a_missing_category(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();
        $device = Device::factory()->make()->toArray();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(0, Device::all());
        $this
            ->actingAs($admin)
            ->post(route('devices.store'), array_merge([$device, 'category_id' => 2]))
            ->assertSessionHasErrors(['category_id' => __('devices.unknown_category')]);
    }

    #[Test]
    public function an_admin_can_see_devices_details(): void
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
            ->get(route('devices.show', $device))
            ->assertOk()
            ->assertSeeTextInOrder([$device->mac, $device->name, $device->description ?? '--']);
    }

    #[Test]
    public function an_admin_can_see_devices_edit_form(): void
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
            ->get(route('devices.edit', $device))
            ->assertOk()
            ->assertSeeInOrder([$device->mac, $device->name, $device->description ?? '--']);
    }

    #[Test]
    public function an_admin_can_update_a_device(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();
        $device = Device::factory()->for($category)->create();
        $device_new = Device::factory()->for($category)->make(['mac' => '01:02:03:04:05:06'])->toArray();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(1, Device::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->patch(route('devices.update', $device), $device_new)
            ->assertOk()
            ->assertSeeText(__('devices.updated', ['name' => $device_new['mac'], 'model' => $category->type]));
    }

    #[DataProvider('invalidDeviceData2')]
    public function test_validation_for_an_existing_device(string $field, mixed $data, string $message): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();
        $device = Device::factory()->for($category)->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(1, Device::all());
        $this
            ->actingAs($admin)
            ->patch(route('devices.update', $device), [$field => $data])
            ->assertSessionHasErrors([$field => $message]);
    }

    public static function invalidDeviceData2(): array
    {
        return [
            ['mac', '', 'The mac field is required.'],
            ['mac', fake()->word(), 'The mac field must be a valid MAC address.'],
            ['name', 'A', 'The name field must be at least 3 characters.'],
            ['name', str_repeat('x', 65), 'The name field must not be greater than 64 characters.'],
            ['description', str_repeat('x', 256), 'The description field must not be greater than 255 characters.'],
            ['enabled', '', 'The enabled field is required.'],
            ['enabled', 'A', 'The enabled field must be true or false.'],
            ['valid_from', fake()->word(), 'The valid from field must be a valid date.'],
            ['valid_to', fake()->word(), 'The valid to field must be a valid date.'],
        ];
    }

    #[Test]
    public function an_admin_can_update_a_device_with_no_change(): void
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
            ->patch(route('devices.update', $device), $device->toArray())
            ->assertOk();
    }

    #[Test]
    public function an_admin_cannot_update_a_device_with_duplicated_mac_address(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();
        $device_old = Device::factory()->for($category)->create();
        $device = Device::factory()->for($category)->create();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(2, Device::all());
        $this
            ->actingAs($admin)
            ->patch(route('devices.update', $device), $device_old->toArray())
            ->assertSessionHasErrors(['mac' => __('devices.uniqueness_required')]);
    }

    #[Test]
    public function an_admin_cannot_update_a_device_with_reserved_mac_address(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);
        $category = Category::factory()->create();
        $device = Device::factory()->for($category)->create();
        $device_new = Device::factory()->for($category)->make(['mac' => '01:00:5e:00:00:00'])->toArray();

        $this->assertCount(1, User::all());
        $this->assertCount(1, Category::all());
        $this->assertCount(1, Device::all());
        $this
            ->actingAs($admin)
            ->patch(route('devices.update', $device), $device_new)
            ->assertSessionHasErrors(['mac' => __('devices.reserved_mac_address')]);
    }

    #[Test]
    public function an_admin_can_delete_a_device(): void
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
            ->delete(route('devices.destroy', $device))
            ->assertOk()
            ->assertSeeText(__('devices.deleted', ['name' => $device->mac, 'model' => $device->type]));
    }

    #[Test]
    public function a_form_to_add_a_device_shows_all_texts(): void
    {
        $admin = User::factory()->create(['active' => true, 'admin' => true]);

        $this->assertCount(1, User::all());
        $this
            ->actingAs($admin)
            ->followingRedirects()
            ->get(route('devices.create'))
            ->assertOk()
            ->assertSeeInOrder([
                __('devices.add'),
                __('devices.device_profile'),
                __('common.category'),
                __('devices.choose_category'),
                __('common.mac'),
                __('inputs.placeholder_mac'),
                __('common.name'),
                __('inputs.placeholder_name'),
                __('common.description'),
                __('inputs.placeholder_description'),
                __('common.status'),
                __('common.enabled'),
                __('common.disabled'),
                __('common.valid_from'),
                __('common.valid_to'),
                __('common.back'),
                __('common.add'),
            ]);
    }
}
