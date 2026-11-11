# TecnoStar — Sistema de Gestión de Cuentas y Suscripciones de Streaming

Sistema web profesional desarrollado a medida para **TecnoStar**, marca con más de 5 años en el mercado y más de 100 clientes activos en la comercialización de cuentas completas y perfiles individuales de plataformas de streaming (Netflix, Disney+, Star+, HBO Max, Prime Video, Spotify, Magis TV, YouTube Premium, Paramount+ y En Vivo Play).

Este proyecto migra la operativa histórica basada en Excel hacia una plataforma web segura, relacional y orientada a la automatización de cobros y control de inventario.

---

## 🚀 Stack Tecnológico

- **Backend:** PHP 8.4+ / Laravel 11/12
- **Base de Datos:** MySQL / MariaDB (charset `utf8mb4`, collation `utf8mb4_unicode_ci`)
- **Frontend:** Vistas Blade (HTML5 + CSS/JS nativo responsivo, sin dependencias complejas de frontend)
- **Entorno de Desarrollo:** XAMPP sobre Windows (puerto 3306)
- **Entorno de Despliegue:** Servidor Linux Ubuntu Server con Apache/Nginx + PHP-FPM + MariaDB
- **Zona Horaria y Región:** `America/Guayaquil` (Ecuador, UTC-5), localización `es`

---

## 🏛️ Arquitectura del Sistema (MVC + Capa de Servicios)

El sistema sigue una separación estricta de responsabilidades:
- **Modelos (`app/Models/`):** Representación de entidades, relaciones Eloquent, scopes de consulta y casts de seguridad (`encrypted` para contraseñas de cuentas de proveedores).
- **Controladores (`app/Http/Controllers/`):** Controladores delgados orientados exclusivamente a la recepción de peticiones HTTP y renderizado de vistas.
- **Validaciones (`app/Http/Requests/`):** Form Requests dedicados para validación estricta de formularios y sanitización de datos.
- **Capa de Servicios (`app/Services/`):**
  - `FinancialReportService`: Métricas financieras en tiempo real (Ingresos, Egresos, Ganancia Neta y Margen).
  - `WhatsAppMessageService`: Generación dinámica de enlaces `wa.me` para cobros (💰), actualización de claves (🔐) y entrega de datos (💻).
  - `SubscriptionLifecycleService`: Control de estados y liberación de perfiles.
  - `ExcelImportService`: Sanitización e ingestión histórica desde el Excel.
- **Vistas (`resources/views/`):** Plantillas Blade modulares en español.

---

## 📊 Estado Actual del Proyecto y Hoja de Ruta

| Fase | Descripción | Estado |
| :---: | :--- | :---: |
| **Fase 0** | **Entorno y Configuración Base** (PHP 8.4, Composer, Git, BD `tecnostar`, `.env`, `AGENT.md`) | ✅ Completada |
| **Fase 1** | **Análisis del Negocio y Requisitos** ([docs/01-analisis.md](docs/01-analisis.md)) | ✅ Completada |
| **Fase 2** | **Diseño de Base de Datos (DER) y Pantallas** ([docs/02-diseno.md](docs/02-diseno.md)) | ✅ Completada |
| **Fase 3** | **Migraciones y Modelos Eloquent** (Tablas, llaves foráneas, índices, casts y relaciones) | ⏳ Siguiente paso |
| **Fase 4** | **Autenticación y CRUDs Base** (Tablero operativo de vencimientos, cuentas, perfiles, clientes) | 🔜 Pendiente |
| **Fase 5** | **Importación de Datos Históricos del Excel** (Sanitizador de precios y teléfonos con auditoría) | 🔜 Pendiente |
| **Fase 6** | **Módulo Financiero y Cobros** (Reportes de ganancia dinámica y registro de pagos) | 🔜 Pendiente |
| **Fase 7** | **Pruebas y Control de Calidad** | 🔜 Pendiente |
| **Fase 8** | **Despliegue en Servidor Ubuntu** (SSL HTTPS, backups automáticos por Cron) | 🔜 Pendiente |
| **Fase 9** | **Automatización Avanzada** (Scheduler para alertas automáticas en base de datos) | 🔜 Pendiente |

---

## 📂 Documentación del Proyecto

Toda la documentación técnica se mantiene en la carpeta [`/docs`](docs/README.md):
- [**01-analisis.md**](docs/01-analisis.md): Diagnóstico exhaustivo del Excel histórico, catálogo de servicios, requisitos funcionales (RF) y no funcionales (RNF).
- [**02-diseno.md**](docs/02-diseno.md): Diagrama Entidad-Relación (DER Mermaid), diccionario de datos MySQL, fórmulas financieras dinámicas y wireframes del tablero de vencimientos.
- [**AGENT.md**](AGENT.md): Guía de referencia técnica para agentes y desarrolladores, reglas mandatorias y registro de memoria del proyecto.

---

## ⚙️ Puesta en Marcha Local (Desarrollo con XAMPP)

1. **Requisitos:**
   - PHP >= 8.2 con extensiones `pdo_mysql`, `openssl`, `mbstring`.
   - Composer 2.x.
   - MySQL / MariaDB (XAMPP activo en puerto 3306).

2. **Configuración de base de datos:**
   - Crear la base de datos `tecnostar`:
     ```sql
     CREATE DATABASE tecnostar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     ```

3. **Variables de entorno:**
   - Copiar `.env.example` a `.env` y configurar las credenciales locales de MySQL.
   - Generar la llave de la aplicación:
     ```bash
     php artisan key:generate
     ```

4. **Ejecutar migraciones:**
   ```bash
   php artisan migrate
   ```

5. **Servidor local:**
   ```bash
   php artisan serve
   ```
