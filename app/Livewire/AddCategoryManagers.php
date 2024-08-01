<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\User;
use Livewire\Component;

class AddCategoryManagers extends Component
{
    public int $category;

    public string $search;

    public $users;

    public function updatedSearch()
    {
        $managers = Category::findOrFail($this->category)
            ->users()
            ->get();

        $this->users = User::query()
            ->search($this->search)
            ->orderBy('name')
            ->limit(3)
            ->get()
            ->diff($managers);
    }

    public function addManager(int $user)
    {
        $user = User::findOrFail($user);

        Category::findOrFail($this->category)
            ->users()
            ->attach($user);

        $this->search = '';
        $this->users = '';

        $this->dispatch('manager-added');
    }

    public function render()
    {
        return view('livewire.add-category-managers');
    }
}
