### Relaciones del Modelo de Datos
1.  **Spaces (Canchas):** Modelo central. Posee una relación de Uno a Muchos con `Reservations`, `Availabilities` y `BlockedSlots`.
2.  **Reservations (Reservas):** Almacena datos del solicitante y tiempos de ocupación. Valida colisiones a nivel de rangos horarios (`start_time`, `end_time`).
3.  **Availabilities (Horarios de Apertura):** Define la estructura operativa base de la semana (0 = Domingo a 6 = Sábado) por cancha. Contiene `start_time`, `end_time` e `is_open`.
4.  **BlockedSlots (Anulación de Horarios):** Registra eventos de mantenimiento impidiendo temporalmente la inserción de reservas en los periodos definidos.

---

## 💻 Guía de Instalación Local

Siga estos pasos rigurosos para desplegar el entorno de desarrollo en su máquina local:

### 1. Clonar el repositorio y acceder
```bash
git clone [https://github.com/TU_USUARIO/reservas-padel-ucaldas.git](https://github.com/TU_USUARIO/reservas-padel-ucaldas.git)
cd reservas-padel-ucaldas
2. Instalar dependencias del Backend (Composer)
Bash
composer install
3. Instalar dependencias del Frontend (NPM)
Bash
npm install
4. Configurar el Entorno del Sistema (.env)
Duplique el archivo de configuración de ejemplo:

Bash
cp .env.example .env
Abra el nuevo archivo .env en su editor de código y configure sus credenciales de PostgreSQL y Mailtrap:

Fragmento de código
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=reservas_padel
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario_mailtrap
MAIL_PASSWORD=tu_password_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="reservas@padelpro.com"
MAIL_FROM_NAME="${APP_NAME}"
5. Generar la Llave de la Aplicación
Bash
php artisan key:generate
6. Ejecutar las Migraciones y Poblar la Base de Datos
Este comando creará el esquema de tablas (incluyendo la adición masiva de columnas como is_open mediante migraciones secundarias) e inyectará los datos iniciales de prueba de las canchas:

Bash
php artisan migrate --seed
7. Levantar los Servidores en Paralelo
Terminal 1 (Backend - Laravel Artisan):

Bash
php artisan serve
Terminal 2 (Frontend - Vite Dev Server):

Bash
npm run dev
Abra su navegador web e ingrese a http://localhost:8000.

📧 Flujo de Notificaciones (Entorno de Desarrollo)
Todos los correos automatizados del sistema (notificaciones de aprobación de reserva, rechazo o cancelaciones) se despachan usando el protocolo SMTP de desarrollo.

Para auditarlos:

Acceda a su bandeja en Mailtrap.io.

Vaya a Email Testing > Inboxes > My Inbox.

Observe los correos entrantes estructurados con las plantillas HTML corporativas desarrolladas para el proyecto.

🧑‍💻 Autoría
Desarrollador: Hanner Edilson Obando Ramirez

Institución: Universidad de Caldas

Docente: Cristian C.

Desarrollado con fines académicos y de portafolio profesional. Todos los derechos reservados © 2026.
"""
