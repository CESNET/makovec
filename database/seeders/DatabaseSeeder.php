<?php

namespace Database\Seeders;

use App\Models\Category;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([CategorySeeder::class]);

        User::factory(2)->create(['active' => true, 'admin' => true]);
        User::factory(2)->create(['active' => true]);
        User::factory(46)->create();

        foreach (Category::all() as $category) {
            for ($i = 0; $i < 10; $i++) {
                Device::factory()
                    ->for($category)
                    ->create([
                        'mac' => $mac = fake()->unique()->macAddress(),
                        'name' => "{$category->type}_{$mac}",
                    ]);
            }
        }
    }
}
