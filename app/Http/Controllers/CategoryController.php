<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
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
        $this->authorize('do-everything');

        return view('categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('do-everything');

        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $this->authorize('do-everything');

        $category = Category::create($request->validated());

        return to_route('categories.show', $category)
            ->with('status', __('categories.added', ['type' => $category->type]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        $this->authorize('do-everything');

        $devices = $category->devices()->count();
        $category->load('users');

        return view('categories.show', compact('category', 'devices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        $this->authorize('do-everything');

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $this->authorize('do-everything');

        $category->update($request->validated());

        if ($category->wasChanged()) {
            return to_route('categories.show', $category)
                ->with('status', __('categories.updated', ['type' => $category->type]));
        }

        return to_route('categories.show', compact('category'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('do-everything');

        if ($category->devices()->count()) {
            return to_route('categories.show', $category)
                ->with('status', __('categories.deleting_category_with_devices_forbidden'))
                ->with('color', 'red');
        }

        $type = $category->type;
        $category->delete();

        return to_route('categories.index')
            ->with('status', __('categories.deleted', compact('type')));
    }
}
