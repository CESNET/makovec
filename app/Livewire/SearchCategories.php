<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Url;
use Livewire\Component;

class SearchCategories extends Component
{
    #[Url(except: '')]
    public string $search = '';

    public function render()
    {
        return view('livewire.search-categories', [
            'categories' => Category::query()
                ->withCount('devices', 'users')
                ->search($this->search)
                ->orderBy('type')
                ->get(),
        ]);
    }
}
