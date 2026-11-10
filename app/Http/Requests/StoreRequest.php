<?php

namespace App\Http\Requests;

use App\Models\Store;
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
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH']);
        $routeStore = $this->route('store');
        $ownerId = $isUpdate && $routeStore ? Store::whereKey($routeStore)->value('user_id') : null;

        return [
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'banner' => ['nullable', 'image', 'max:4096'],

            'owner_name' => [$isUpdate ? 'sometimes' : 'required', 'string', 'max:120'],

            'owner_email' => [
                $isUpdate ? 'sometimes' : 'required',
                'email',
                'max:120',
                Rule::unique('users', 'email')->ignore($ownerId),
            ],

            'owner_password' => $isUpdate
                ? ['nullable', 'string', 'min:6', 'max:20']
                : ['required', 'string', 'min:6', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'owner_email.unique' => 'This email is already in use.',
        ];
    }
}
