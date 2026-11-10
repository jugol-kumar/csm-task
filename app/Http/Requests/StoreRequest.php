<?php

namespace App\Http\Requests;

use App\Contract\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],

            'owner_name' => ['string', 'max:120'],
            'owner_email' => ['string', 'unique:users,email', 'max:120'],
            'owner_password' => ['string', 'max:20', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'owner_id.unique' => 'This owner already has a shop.',
        ];
    }
}
