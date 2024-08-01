<?php

namespace App\Http\Requests;

use App\Rules\UniqueVlanUpdate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'alpha:ascii', 'min:2', 'max:32', Rule::unique('categories')->ignore($this->route('category'))],
            'description' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($this->route('category'))],
            'vlan' => ['required', 'regex:/^[a-zA-Z_\ ]+$/i', 'max:64', new UniqueVlanUpdate],
        ];
    }
}
