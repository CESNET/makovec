<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueVlanUpdate implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $id = request()->segment(2);

        if (Category::whereVlan('vlan_'.$value)->whereNot('id', $id)->count() !== 0) {
            $fail(__('categories.uniqueness_required'));
        }
    }
}
