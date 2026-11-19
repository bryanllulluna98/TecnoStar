<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProviderAccountRequest extends FormRequest
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
            'password' => ['required', 'string', 'max:255'],
            'cost' => ['required', 'numeric', 'min:0'],
            'billing_start_date' => ['nullable', 'date'],
            'billing_due_date' => ['required', 'date'],
            'max_profiles' => ['required', 'integer', 'min:1', 'max:20'],
            'status' => ['required', 'in:active,suspended,cancelled'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required' => 'Debes seleccionar una plataforma de streaming.',
            'email.required' => 'El correo o usuario de la cuenta es obligatorio.',
            'password.required' => 'La contraseña de la cuenta es obligatoria.',
            'cost.required' => 'El costo que se paga al proveedor es obligatorio.',
            'billing_due_date.required' => 'La fecha de corte con el proveedor es obligatoria.',
            'max_profiles.required' => 'Debes indicar el número máximo de perfiles.',
        ];
    }
}
