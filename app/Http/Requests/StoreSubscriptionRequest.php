<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'profile_id' => ['required', 'exists:profiles,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'starts_at' => ['required', 'date'],
            'expires_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'renewal_period' => ['required', 'in:monthly,quarterly,biannual,annual'],
            'record_payment' => ['nullable', 'boolean'],
            'payment_method' => ['nullable', 'string', 'in:transfer_pichincha,transfer_guayaquil,deuna,cash,other'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'Debes seleccionar un cliente.',
            'profile_id.required' => 'Debes seleccionar un perfil disponible.',
            'price.required' => 'El precio acordado es obligatorio.',
            'starts_at.required' => 'La fecha de inicio es obligatoria.',
            'expires_at.required' => 'La fecha de vencimiento es obligatoria.',
            'expires_at.after_or_equal' => 'La fecha de vencimiento debe ser posterior o igual al inicio.',
        ];
    }
}
