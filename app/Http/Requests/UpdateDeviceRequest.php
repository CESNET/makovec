<?php

namespace App\Http\Requests;

use App\Rules\UniqueMacUpdate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDeviceRequest extends FormRequest
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
            'mac' => ['required', 'mac_address', new UniqueMacUpdate],
            'name' => ['required', 'string', 'min:3', 'max:64', Rule::unique('devices')->ignore($this->route('device'))],
            'description' => 'string|max:255|nullable',
            'enabled' => 'required|boolean',
            'valid_from' => 'date|nullable',
            'valid_to' => 'date|nullable',
        ];
    }
}
