<?php

namespace Database\Seeders;

use App\Models\MessageTemplate;
use Illuminate\Database\Seeder;

class MessageTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'code' => 'billing_reminder',
                'title' => 'Recordatorio de Cobro / Renovación (💰)',
                'body' => "👋 ¡Hola {{client_name}}!\nTe saludamos de *TecnoStar* ⭐.\nTe recordamos que tu perfil de *{{service_name}}* (Perfil: *{{profile_name}}*) vence el *{{expires_at}}*.\n💰 *Monto a renovar:* \${{price}}\n\nPor favor envíanos el comprobante para mantener tu servicio activo. ¡Muchas gracias! 🙌",
                'is_active' => true,
            ],
            [
                'code' => 'password_update',
                'title' => 'Actualización de Credenciales (🔐)',
                'body' => "🔐 *Actualización de Credenciales - TecnoStar* ⭐\n\nHola {{client_name}}, se ha actualizado la contraseña de tu cuenta:\n🎬 *Servicio:* {{service_name}}\n📧 *Correo:* {{account_email}}\n🔑 *Nueva Contraseña:* {{account_password}}\n👤 *Tu Perfil:* {{profile_name}} (PIN: {{pin}})\n\n⚠️ *Recordatorio:* Recuerda no modificar los datos de la cuenta ni compartir el perfil. ¡Disfrútalo! 🍿",
                'is_active' => true,
            ],
            [
                'code' => 'credential_delivery',
                'title' => 'Entrega de Perfil / Bienvenida (💻)',
                'body' => "🎬 *¡Bienvenido a TecnoStar!* ⭐\n\nHola {{client_name}}, aquí tienes los datos de acceso para tu servicio:\n📺 *Servicio:* {{service_name}}\n📧 *Correo:* {{account_email}}\n🔑 *Contraseña:* {{account_password}}\n👤 *Perfil asignado:* {{profile_name}}\n🔒 *PIN:* {{pin}}\n📅 *Válido hasta:* {{expires_at}}\n\n⚠️ *Reglas:* \n- 1 solo dispositivo a la vez por perfil.\n- No cambiar la contraseña ni agregar PIN a otros perfiles.\n\n¡Cualquier duda estamos a la orden! 🍿",
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            MessageTemplate::updateOrCreate(['code' => $template['code']], $template);
        }
    }
}
