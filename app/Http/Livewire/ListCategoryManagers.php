<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ListCategoryManagers extends Component
{
    public int $category;

    public Collection $users;

    protected $listeners = ['refreshList' => '$refresh'];

    public function deleteManager(int $manager)
    {
        $user = User::findOrFail($manager);

        Category::findOrFail($this->category)
            ->users()
            ->detach($user);
    }

    public function render()
    {
        $this->users = Category::findOrFail($this->category)
            ->users()
            ->orderBy('name')
            ->get();

        return view('livewire.list-category-managers');
    }
}
