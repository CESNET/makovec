<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Models\Category;
use App\Models\Device;
use App\Models\User;
use App\Services\MacService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DeviceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Device::class);

        $types = auth()->user()->admin
            ? Category::pluck('type')
            : User::findOrFail(Auth::id())->categories()->pluck('type');

        return view('devices.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Device::class);

        $categories = auth()->user()->admin
            ? Category::select('id', 'type')->get()
            : User::findOrFail(Auth::id())->categories()->select('categories.id', 'type')->get();

        return view('devices.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeviceRequest $request, MacService $macService): RedirectResponse
    {
        $this->authorize('create', Device::class);

        $category = Category::findOrFail($request->validated()['category_id']);
        $device = Device::create(array_merge(
            $request->validated(),
            ['name' => $request->name
                ?? $category->type.'_'.$macService->clean($request->validated()['mac'])],
        ));

        return to_route('devices.show', $device)
            ->with('status', __('devices.added', ['name' => $device->mac, 'category' => $category->description]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device): View
    {
        $this->authorize('view', $device);

        $device->load('logCreated', 'logUpdated');

        parse_str(
            parse_url(
                url()->previous()
            )['query'] ?? null,
            $array
        );
        $query = Arr::only($array, ['type', 'page']);

        return view('devices.show', compact('device', 'query'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device): View
    {
        $this->authorize('update', $device);

        return view('devices.edit', compact('device'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeviceRequest $request, Device $device): RedirectResponse
    {
        $this->authorize('update', $device);

        $device->update($request->validated());

        if ($device->wasChanged()) {
            return to_route('devices.show', $device)
                ->with('status', __('devices.updated', ['name' => $device->mac]));
        }

        return to_route('devices.show', $device);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device): RedirectResponse
    {
        $this->authorize('delete', $device);

        $name = $device->mac;
        $device->delete();

        return to_route('devices.index')
            ->with('status', __('devices.deleted', compact('name')));
    }
}
