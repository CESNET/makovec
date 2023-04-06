<?php

namespace App\Rules;

use App\Models\Category;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class KnownDeviceType implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $users_categories = User::with('categories:type')->findOrFail(Auth::id())->categories()->pluck('categories.id')->toArray();
        $admin = User::findOrFail(Auth::id())->admin;
        $deny = $admin ? false : ! in_array(request('category_id'), $users_categories);

        if ($deny) {
            $fail(__('devices.no_permissions_for_this_category'));
        }

        $count = Category::find(request('category_id'));

        if (! $count) {
            $fail(__('devices.unknown_category'));
        }
    }
}
