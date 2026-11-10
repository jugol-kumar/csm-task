<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $owner = $this->route('users');

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($owner?->id)],
            'password' => [$owner ? 'nullable' : 'required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['customer', 'owner', 'admin'])],
            'shop_id' => [
                'nullable',
                'integer',
                Rule::exists('shops', 'id')->where(function ($query) use ($owner) {
                    $query->where(function ($inner) use ($owner) {
                        $inner->whereNull('user_id');
                        if ($owner) {
                            $inner->orWhere('user_id', $owner->id);
                        }
                    });
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'shop_id.exists' => 'Pick a shop that does not already have an owner.',
        ];
    }
}
