<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SearchDevices extends Component
{
    use WithPagination;

    public string $search = '';

    public string $type = '';

    public string $sort = '';

    public string $order = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'sort' => ['except' => ''],
        'order' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $sort = $this->sort ?: 'mac';
        $order = strcasecmp($this->order, 'desc') === 0 ? 'DESC' : 'ASC';

        $types = auth()->user()->admin || auth()->user()->manager
            ? Category::pluck('type')
            : User::findOrFail(Auth::id())->categories()->pluck('type');

        $devices = Device::query()
            ->with('category')
            ->search($this->search)
            ->whereHas('category', function (Builder $query) use ($types) {
                $query
                    ->where('type', 'like', "%$this->type%")
                    ->whereIn('type', $types);
            })
            ->orderBy($sort, "$order")
            ->paginate();

        return view('livewire.search-devices', compact('devices'));
    }
}
