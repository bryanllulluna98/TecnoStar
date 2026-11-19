<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProviderAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', 'exists:services,id'],
            'account_identifier' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:255'],
            'cost' => ['required', 'numeric', 'min:0'],
            'billing_start_date' => ['nullable', 'date'],
            'billing_due_date' => ['required', 'date'],
            'max_profiles' => ['required', 'integer', 'min:1', 'max:20'],
            'status' => ['required', 'in:active,suspended,cancelled'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
