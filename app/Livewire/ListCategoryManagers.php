<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class ListCategoryManagers extends Component
{
    public int $category;

    public Collection $users;

    public function deleteManager(int $manager)
    {
        $user = User::findOrFail($manager);

        Category::findOrFail($this->category)
            ->users()
            ->detach($user);
    }

    #[On('manager-added')]
    public function render()
    {
        $this->users = Category::findOrFail($this->category)
            ->users()
            ->orderBy('name')
            ->get();

        return view('livewire.list-category-managers');
    }
}
