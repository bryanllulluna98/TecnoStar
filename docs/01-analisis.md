# Fase 1: Análisis del Negocio y Especificación de Requisitos

**Proyecto:** TecnoStar — Sistema de Gestión de Cuentas y Suscripciones de Streaming  
**Autor:** Ingeniero de Software Senior  
**Estado:** Completado y Aprobado  
**Fecha:** Noviembre 2026  

---

## 1. Introducción y Visión General

**TecnoStar** es una marca personal con más de 5 años de trayectoria y una base de más de 100 clientes activos en la venta de cuentas completas y perfiles individuales de plataformas de streaming (Netflix, Disney+, Star+, HBO Max, Prime Video, Spotify, Magis TV, YouTube Premium, Paramount+ y En Vivo Play).

Hasta hoy, la gestión global se ha realizado en un archivo Excel multi-bloque (`Ultimo_1_chat_1_.xlsx`). El objetivo de este proyecto es migrar dicha operativa a un sistema web propio en Laravel + MySQL, robusto, seguro y diseñado bajo el patrón MVC con capa de servicios especializada.

---

## 2. Radiografía Técnica del Archivo Excel (`Ultimo_1_chat_1_.xlsx`)

A partir del análisis exhaustivo del volcado real de datos del archivo Excel, se identifican las estructuras, secciones y patrones exactos que maneja el negocio:

### 2.1. Estructura de Columnas Reales
El archivo maneja bloques tabulares que combinan hasta 17 columnas por fila:
1. `Cuenta`: Número secuencial que agrupa una cuenta madre (ej. `1`, `2`, `3`).
2. `Nombre`: Nombre del cliente (ej. `Cliente Jhonatan`, `Mireya Farinango`, `Kevin Vinueza`). En ocasiones contiene números telefónicos o está vacío.
3. `Celular`: Teléfono del cliente. Aparece en múltiples formatos (`+593 99 863 7279`, `593 99 773 5346`, `+34 615 99 43 49` de España, o cadenas como `Amor` o vacío).
4. `Perfil`: Nombre del perfil asignado dentro de la plataforma (ej. `Jhonatan `, `Joanna`, `Mireya`, `Kids`).
5. `Pin`: Código PIN de 4 dígitos (ej. `8888`, `1248`, `4444`) o texto como `sn` (sin pin).
6. `Correo`: Correo electrónico de la cuenta madre con el proveedor (ej. `starstreaming593@gmail.com`, `star.stream361@gmail.com`).
7. `Pass`: Contraseña de la cuenta madre expuesta en texto plano (ej. `Star1234@`, `admin456@@`, `gonza20266`).
8. `Pago`: Monto abonado por el cliente. Presenta alta suciedad (`4`, `2.5`, `5`, `"5,5"`, `"4,50$"`, `0`, `20`, `"4,49"`, `NaN`).
9. `Suscripción`: Fecha de inicio del servicio para el cliente.
10. `Fin de suscripción`: Fecha de vencimiento del perfil del cliente.
11. `Días`: Días restantes para el vencimiento del cliente (fórmula calculada contra `Fecha de hoy`).
12. `Cuenta inicio`: Fecha de compra de la cuenta madre al proveedor mayorista.
13. `Cuenta fin`: Fecha de corte de la cuenta madre con el proveedor.
14. `Dias` (proveedor): Días restantes de vigencia de la cuenta madre antes de que el proveedor la corte.
15. `Fecha de hoy`: Fecha estática de cálculo en la hoja (ej. `2026-11-11`).
16. `PRECIO` / `Costo`: Costo real que se paga al proveedor por la cuenta completa (ej. `$7.00`, `$12.00`, `$14.00`, `$17.50`, `$3.50`, `$2.70`, `$1.50`, `$2.50`).
17. `Botones WhatsApp`: Columnas `COBRAR` (💰), `ACTUALIZAR` (🔐), `PERFILES` (💻), las cuales contenían enlaces manuales `https://wa.me/...`.

### 2.2. Bloques Financieros Manuales
Al pie de cada bloque de servicio se registran totales escritos a mano o con fórmulas rígidas propensas a errores de redondeo por punto flotante:
- Ejemplo real detectado: `INGRESOS: 120.99000000000001`, `EGRESOS: 86.5`, `GANANCIAS: 34.490000000000009`.
- Bloques con márgenes negativos o cero: `INGRESOS: 0`, `EGRESOS: 2.5`, `GANANCIAS: -2.5`.

---

## 3. Catálogo de Servicios Detectados en el Negocio

El sistema TecnoStar soportará nativamente dos modalidades de venta:
1. **Venta por Perfiles (Pantallas):** Una cuenta madre se divide en múltiples perfiles individuales con su propio PIN.
   - **Netflix** (4-5 perfiles, PIN de 4 dígitos).
   - **Disney+ / Star+** (hasta 7 perfiles).
   - **HBO Max (Max)** (hasta 5 perfiles).
   - **Prime Video** (hasta 6 perfiles).
2. **Venta por Cuentas Completas / Personales:** La cuenta se entrega de forma íntegra a un solo cliente o familia.
   - **Spotify** (cuentas individuales de 1 a 3 meses, o planes familiares anuales de $20).
   - **Magis TV** (conexiones por usuario y clave con vigencia mensual/anual).
   - **YouTube Premium** (cuentas familiares/individuales por correo).
   - **Paramount+** y **En Vivo Play**.

---

## 4. Desacoplamiento del Modelo de Dominio

El diseño normalizado reemplaza la fila plana de Excel por entidades bien delimitadas:

```
+--------------------+        +-----------------------+
|      services      | 1 ---- |   provider_accounts   | (Cuentas Madre)
| (Netflix, Spotify) |        +-----------------------+
+--------------------+                    | 1
          |                               |
          |                               *
          |                   +-----------------------+
          +-----------------> |       profiles        | (Perfiles vendibles)
                              +-----------------------+
                                          | 1
                                          |
                                          *
+--------------------+        +-----------------------+
|      clients       | 1 ---- |     subscriptions     | (Ventas / Contratos)
| (Datos normaliz.)  |        +-----------------------+
+--------------------+                    | 1
                                          |
                                          *
                              +-----------------------+
                              |       payments        | (Abonos reales)
                              +-----------------------+
```

### Reglas de Relación:
- Una cuenta madre (`provider_accounts`) pertenece a un `service`.
- Si el servicio es por perfiles, la cuenta madre tiene múltiples `profiles` (`hasMany`).
- Si el servicio es de cuenta completa (ej. Magis TV o Spotify individual), la cuenta madre posee 1 único perfil virtual que representa la cuenta entera.
- Un `client` puede tener múltiples `subscriptions` activas a distintos servicios.
- Cada `subscription` pertenece a un `client` y a un `profile`.
- Los `payments` se registran contra la `subscription`, permitiendo abonos parciales, pagos anticipados y trazabilidad histórica.

---

## 5. Requisitos Funcionales Detallados (RF)

### Módulo de Cuentas y Stock
- **RF-01 (Gestión de Servicios):** CRUD de servicios indicando nombre, tipo (`profile` o `full_account`), precio sugerido de venta y perfiles máximos.
- **RF-02 (Cuentas Madre Cifradas):** Registrar cuentas con correo, contraseña cifrada (`cast: encrypted`), fecha de inicio, fecha de fin y costo con el proveedor.
- **RF-03 (Generación Automática de Perfiles):** Al crear una cuenta madre, generar sus perfiles en estado `free`, permitiendo fijar el PIN respectivo.

### Módulo de Clientes y Suscripciones
- **RF-04 (Clientes Unificados):** Registrar clientes evitando duplicados. Normalizar celular al estándar internacional E.164 (`+593...` para Ecuador, `+34...` para España, etc.).
- **RF-05 (Venta / Activación de Suscripción):** Asignar un perfil libre (`free`) a un cliente, definir precio pactado (`decimal(8,2)`), fecha de inicio y fin, cambiando automáticamente el perfil a `occupied`.
- **RF-06 (Renovación y Liberación):**
  - Al renovar: extender `expires_at` y generar el pago correspondiente.
  - Al cancelar: marcar la suscripción como `cancelled` y regresar el perfil a estado `free`.

### Módulo Financiero
- **RF-07 (Cálculo Financiero Dinámico en Tiempo Real):**
  - **Ingresos:** `SUM(payments.amount)` en el período seleccionado.
  - **Egresos:** `SUM(provider_accounts.cost)` de cuentas proveedor activas en el período.
  - **Ganancia Neta:** `Ingresos - Egresos`.
  - Sin campos estáticos de total en base de datos.
- **RF-08 (Registro de Pagos):** Registro de pagos con fecha, monto (`decimal(8,2)`), método de pago (Pichincha, Guayaquil, Deuna, Efectivo, etc.) y referencia opcional.

### Módulo Operativo y WhatsApp
- **RF-09 (Tablero de Control de Vencimientos):**
  - Pestaña 1: Clientes que vencen hoy (alerta crítica).
  - Pestaña 2: Clientes que vencen en los próximos 1 a 3 días (preventivo).
  - Pestaña 3: Clientes vencidos con servicio activo (para corte o cobro).
  - Pestaña 4: Cuentas madre próximas a vencer con el proveedor mayorista.
- **RF-10 (Automatización de WhatsApp):**
  - Botón **COBRAR** (💰): Genera enlace `https://wa.me/{celular}?text=...` con saludo personalizado, servicio, fecha de corte y valor a cancelar.
  - Botón **ACTUALIZAR** (🔐): Genera enlace notificando nueva contraseña o PIN asignado.
  - Botón **PERFILES / DATOS** (💻): Genera enlace de bienvenida con correo, perfil asignado, PIN y reglas de uso (no cambiar clave, 1 solo dispositivo por perfil).

### Módulo de Ingestión y Migración (Fase 5)
- **RF-11 (Importador del Excel Histórico):**
  - Procesador para leer las filas y normalizar los datos sucios (regex para precios `"5,5"` -> `5.50`, `"4,50$"` -> `4.50`).
  - Detección de números de teléfono embebidos en el nombre.
  - Reporte de auditoría con clientes que requieran revisión manual por falta de contacto.

---

## 6. Requisitos No Funcionales (RNF)

- **RNF-01 (Seguridad y Privacidad):**
  - Cifrado simétrico AES-256-CBC para contraseñas de cuentas proveedor (`provider_accounts.password`) mediante el atributo `$casts = ['password' => 'encrypted']`.
  - Protección estricta: `.env`, volcados de datos y respaldos nunca deben incluirse en Git.
- **RNF-02 (Rendimiento):**
  - Carga ágil de listados (< 200ms) mediante eager loading (`with(['client', 'profile.providerAccount'])`) previniendo consultas N+1.
  - Índices en columnas clave: `clients.phone`, `subscriptions.status`, `subscriptions.expires_at`, `provider_accounts.billing_due_date`.
- **RNF-03 (Arquitectura Limpia):**
  - MVC estricto: Modelos con relaciones y casts, vistas Blade limpias sin lógica de negocio, controladores delgados, validaciones en FormRequests y lógica compleja en Servicios (`app/Services`).
- **RNF-04 (Configuración Regional):**
  - Moneda: Dólares estadounidenses (USD) con formato `decimal(8,2)`.
  - Zona horaria: `America/Guayaquil` (UTC-5).
  - Idioma de la aplicación: Español.

---

## 7. Criterios de Aceptación de la Fase 1
1. [x] Volcado real del archivo Excel analizado y clasificado.
2. [x] Documento `docs/01-analisis.md` actualizado con el detalle de columnas, cuentas y modalidades de venta.
3. [x] Matriz de requisitos funcionales y no funcionales aprobada para pasar a la Fase 2 (Diseño).
