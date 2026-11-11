# AGENT.md - TecnoStar

Guía central del proyecto TecnoStar para agentes y desarrolladores.

## 1. Contexto del Proyecto
**TecnoStar** es una marca personal con más de 5 años en el mercado y más de 100 clientes activa en la venta de cuentas y perfiles de streaming (Netflix, Disney+, Max/HBO, Spotify, etc.).
Actualmente la gestión se encuentra en un archivo Excel (`Ultimo_1_chat_1_.xlsx`) y se migra a un sistema web propio.

### Problemas que resuelve el sistema
- Hojas aisladas con totales de ingresos, egresos y ganancias calculados a mano.
- Inconsistencia en datos: precios en formatos mixtos (`5,5`, `5.5`, `4,50$`), fechas como números seriales, clientes duplicados y números telefónicos incompletos.
- Contraseñas almacenadas en texto plano junto a datos personales.
- Enlaces de WhatsApp generados manualmente.
- Filas que mezclan proveedor (cuenta madre), producto (perfil) y venta (cliente + suscripción).

---

## 2. Stack Tecnológico
- **Lenguaje:** PHP 8.4+ (requerido >= 8.2)
- **Framework:** Laravel 11/12
- **Base de Datos:** MySQL / MariaDB (charset `utf8mb4`, collation `utf8mb4_unicode_ci`)
- **Entorno de Desarrollo:** XAMPP sobre Windows
- **Frontend:** Vistas Blade (HTML5 + CSS/JS nativo). **No** usar Livewire, Docker, Redis ni PostgreSQL.
- **Entorno de Producción:** Servidor local Linux (Ubuntu) con Apache/Nginx + PHP-FPM + MariaDB.

---

## 3. Arquitectura del Software (MVC)
- **Modelos:** `app/Models` (definición de casts, relaciones Eloquent y mutadores).
- **Vistas:** `resources/views` (plantillas Blade en español).
- **Controladores:** `app/Http/Controllers` (controladores delgados, únicamente orquestación HTTP).
- **Validaciones:** `app/Http/Requests` (Form Requests dedicados para cada formulario).
- **Lógica de Negocio y Procesos Complejos:** `app/Services` (importación de Excel, cálculos financieros, integración de mensajes).

---

## 4. Reglas Obligatorias de Trabajo
1. **Un paso a la vez:** Avanzar estrictamente un paso por iteración. Detenerse, mostrar el resultado y esperar confirmación del usuario.
2. **Explicación previa:** Antes de ejecutar cualquier comando, indicar cuál es, para qué sirve y explicar el concepto técnico.
3. **Integridad de archivos:** Nunca borrar, renombrar ni sobrescribir archivos o carpetas sin autorización explícita previa del usuario.
4. **Fidelidad de datos:** No inventar columnas ni valores del Excel. Consultar ante cualquier ambigüedad.
5. **Seguridad:**
   - Nunca incluir `.env` ni datos reales en Git.
   - Las contraseñas de cuentas de proveedores deben guardarse cifradas usando el cast `encrypted` de Laravel.
6. **Convenciones:**
   - Tablas y columnas en inglés en singular/plural según convención Laravel (`snake_case`).
   - Textos de interfaz y mensajes al usuario en español.
   - Valores monetarios con tipo `decimal(8,2)`.
   - Fechas con tipo `date` o `datetime` según corresponda.
   - Commits granulares por paso con prefijos semánticos: `feat:`, `fix:`, `docs:`, `chore:`.
7. **Documentación:**
   - Mantener documentación detallada en `/docs`.
   - Mantener actualizado este archivo `AGENT.md`.

---

## 5. Modelo de Datos Previsto
- `clients`: nombre, celular, notas.
- `services`: plataformas (Netflix, Disney, etc.) con precio sugerido.
- `provider_accounts`: cuenta madre (servicio, correo, clave cifrada, costo, fecha de corte, perfiles máximos).
- `profiles`: perfiles asociados a la cuenta madre (nombre, PIN, estado `free`/`occupied`).
- `subscriptions`: relación cliente - perfil (precio acordado, fecha inicio, fecha fin, estado).
- `payments`: registro de pagos (suscripción, monto, fecha, método).
- `message_templates`: plantillas dinámicas de cobro, entrega y renovación para WhatsApp.

*Nota:* Ingresos, egresos y ganancias se derivarán mediante consultas agregadas sobre `payments` y `provider_accounts`, nunca como valores fijos manuales.

---

## 6. Hoja de Ruta y Estado de Avance
- [x] **Fase 0: Entorno (Laravel + MySQL + XAMPP)**
  - [x] Paso 1: Verificación de versiones y entorno (PHP 8.4, Composer 2.10, Git, extensiones).
  - [x] Paso 2: Instalación de Laravel e inicialización del repositorio Git.
  - [x] Paso 3: Creación de BD `tecnostar`, configuración de `.env` (`es`, `America/Guayaquil`, MySQL) y ejecución de migraciones base.
  - [x] Paso 4: Creación de `AGENT.md` y estructura de `/docs`.
- [x] **Fase 1: Análisis y requisitos (`docs/01-analisis.md`)**
- [x] **Fase 2: Diseño de base de datos y pantallas (`docs/02-diseno.md`)**
- [ ] **Fase 3: Migraciones y modelos**

---

## 7. Registro de Decisiones de Arquitectura y Memoria del Proyecto

### 7.1. Decisiones Técnicas Clave
- **Seguridad en reposo (Regla 5):** `provider_accounts.password` usa el cast `encrypted` de Laravel (cifrado AES-256-CBC). Nunca se guarda en texto claro en la base de datos.
- **Tipado Monetario:** Toda columna financiera (`cost`, `price`, `amount`, `suggested_price`) se define estrictamente como `DECIMAL(8,2)`.
- **Cálculo Financiero Dinámico:** Ingresos, egresos y ganancias netas se derivan en tiempo real de consultas `SUM()` sobre `payments` y `provider_accounts`. Se prohíben campos estáticos de totales en la base de datos.
- **Normalización de Teléfonos:** Formato estándar internacional E.164 (`+593...` para Ecuador, `+34...` para clientes extranjeros). Clientes sin teléfono o dudosos se marcan con el flag booleano `needs_contact_review = true`.
- **Modalidades de Servicio:** Soporte para servicios por perfiles (Netflix, Disney+, HBO Max, Prime Video) y cuentas completas (Spotify individual/familiar, Magis TV).
- **Control de Vencimientos:** Tablero operativo con categorización por urgencia (vencen hoy, vencen en 1-3 días, vencidos sin cobrar) y disparadores de WhatsApp codificados (`wa.me`).

### 7.2. Estado del Repositorio y Entorno
- **Rama:** `master`
- **Base de datos:** `tecnostar` sobre MySQL (MariaDB XAMPP puerto 3306) con cotejamiento `utf8mb4_unicode_ci`.
- **Commits realizados:**
  - `46c21a4`: `chore: initial Laravel project setup`
  - `affeb1a`: `chore: configure database connection, timezone and locale`
  - `8028b23`: `docs: add AGENT.md and docs structure for phase tracking`
  - `7be5d48`: `docs: complete Phase 1 business analysis and requirements based on Excel data`
- [ ] **Fase 4: Login y CRUDs base (clientes, servicios, cuentas, perfiles, suscripciones, tablero de vencimientos)**
- [ ] **Fase 5: Importación del Excel con reporte de inconsistencias**
- [ ] **Fase 6: Cobros, pagos y reportes de ganancia**
- [ ] **Fase 7: Pruebas del sistema**
- [ ] **Fase 8: Despliegue en Ubuntu con HTTPS y respaldos automáticos**
- [ ] **Fase 9: Recordatorios automáticos (Laravel Scheduler y colas en BD)**
