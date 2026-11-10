<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['customer', 'owner', 'admin'])],

            'store_id' => [
                'nullable',
                'integer',
                'prohibited_unless:role,owner',
                'prohibits:new_store',
                Rule::exists('stores', 'id')->where(function ($query) use ($user) {
                    $query->where(function ($inner) use ($user) {
                        $inner->whereNull('user_id');
                        if ($user) {
                            $inner->orWhere('user_id', $user->id);
                        }
                    });
                }),
            ],

            'new_store' => ['nullable', 'array', 'prohibited_unless:role,owner'],
            'new_store.name' => ['required_with:new_store', 'string', 'max:120'],
            'new_store.slug' => [
                'required_with:new_store',
                'string',
                'max:120',
                'alpha_dash',
                Rule::unique('stores', 'slug'),
            ],
            'new_store.description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'store_id.exists' => 'Pick a store that does not already have an owner.',
            'store_id.prohibits' => 'Choose an existing store or create a new one, not both.',
            'store_id.prohibited_unless' => 'Only owners can be linked to a store.',
            'new_store.prohibited_unless' => 'Only owners can have a new store.',
            'new_store.slug.unique' => 'This store slug is already taken.',
        ];
    }
}
