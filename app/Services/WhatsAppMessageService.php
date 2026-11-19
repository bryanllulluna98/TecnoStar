<?php

namespace App\Services;

use App\Models\Client;
use App\Models\MessageTemplate;
use App\Models\Subscription;

class WhatsAppMessageService
{
    /**
     * Limpia y normaliza el número de teléfono al formato internacional (ej. 593987654321).
     */
    public function sanitizePhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Remover caracteres no numéricos
        $clean = preg_replace('/\D+/', '', $phone);

        if (empty($clean)) {
            return null;
        }

        // Si empieza con 09 (móvil Ecuador estándar), reemplazar por código país 593
        if (str_starts_with($clean, '09') && strlen($clean) === 10) {
            $clean = '593' . substr($clean, 1);
        }

        return $clean;
    }

    /**
     * Construye el enlace de WhatsApp codificado a partir de una plantilla y una suscripción.
     */
    public function generateUrl(string $templateCode, Subscription $subscription): ?string
    {
        $subscription->loadMissing(['client', 'profile.providerAccount.service']);

        $client = $subscription->client;
        $profile = $subscription->profile;
        $account = $profile?->providerAccount;
        $service = $account?->service;

        $phone = $this->sanitizePhone($client?->phone);
        if (!$phone) {
            return null;
        }

        $template = MessageTemplate::where('code', $templateCode)->where('is_active', true)->first();
        if (!$template) {
            return null;
        }

        $replacements = [
            '{{client_name}}' => $client?->name ?? 'Cliente',
            '{{service_name}}' => $service?->name ?? 'Streaming',
            '{{profile_name}}' => $profile?->name ?? 'Perfil',
            '{{pin}}' => $profile?->pin ?? 'Sin PIN',
            '{{price}}' => number_format((float) $subscription->price, 2),
            '{{starts_at}}' => $subscription->starts_at?->format('d/m/Y') ?? '',
            '{{expires_at}}' => $subscription->expires_at?->format('d/m/Y') ?? '',
            '{{account_email}}' => $account?->email ?? '',
            '{{account_password}}' => $account?->password ?? '', // Se descifra automáticamente gracias al cast
        ];

        $message = str_replace(array_keys($replacements), array_values($replacements), $template->body);

        return 'https://wa.me/' . $phone . '?text=' . urlencode($message);
    }
}
