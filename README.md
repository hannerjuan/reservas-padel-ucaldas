Markdown
# Sistema de Reservas - Canchas de Pádel

Proyecto final desarrollado para la asignatura de Framework Laravel. Es una plataforma web para la gestión y reserva de canchas de pádel, construida bajo el stack VILT (Vue, Inertia, Laravel, Tailwind) y utilizando PostgreSQL.

## Requisitos Previos
- PHP 8.2 o superior
- Composer
- Node.js y NPM
- PostgreSQL

## Instrucciones de Instalación y Ejecución

1. Clonar el repositorio.
2. Instalar las dependencias de backend:
   ```bash
   composer install
Instalar las dependencias de frontend:

Bash
npm install
Duplicar el archivo .env.example y renombrarlo a .env.

Configurar las credenciales de PostgreSQL y Mailtrap en el archivo .env. Además, asegúrese de agregar la regla de negocio al final del archivo:
RESERVATION_SLOT_MINUTES=60

Generar la llave de la aplicación:

Bash
php artisan key:generate
Ejecutar las migraciones y los seeders (esto creará las tablas y poblará la base de datos con las 3 canchas de pádel iniciales):

Bash
php artisan migrate --seed
En dos terminales separadas, levantar los servidores de Vite y Laravel:

Bash
npm run dev
Bash
php artisan serve
El proyecto estará disponible en http://localhost:8000.