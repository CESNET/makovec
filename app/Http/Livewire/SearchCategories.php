<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;

class SearchCategories extends Component
{
    public string $search = '';

    protected $queryString = ['search' => ['except' => '']];

    public function render()
    {
        $categories = Category::query()
            ->withCount('devices', 'users')
            ->search($this->search)
            ->orderBy('type')
            ->get();

        return view('livewire.search-categories', compact('categories'));
    }
}
