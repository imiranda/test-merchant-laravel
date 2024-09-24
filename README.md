# Proyecto desafio tecnico Laravel con Vue.js

**Descripción:**
Este proyecto es una aplicación cuya finalidad es mostrar algunas competencias tecnicas

**Tecnologías:**
* Laravel 10
* Vue.js 3
* Bootstrap 5

**Instalación Backend:**

1. Clonar el repositorio:
   ```bash
   git clone git@github.com:imiranda/test-merchant-laravel.git

2. ir a carpeta:
   ```bash
   cd /test-merchant-laravel

3. Copiar .env.example a .env:
   ```bash
   cp .env.example .env

4. Instalar dependencias laravel:
   ```bash
   composer install

5. Generar key laravel:
   ```bash
   php artisan key:generate

6. Dejar que laravel cree la base de datos y las tablas con migrations, importante considerar en nombre de la base de datos del archivo .env:
   ```bash
   php artisan migrate

7. Ejecutar backend laravel:
   ```bash
   php artisan serve

3. ir a navegador y veras las rutas en swagger:
   ```bash
   http://127.0.0.1:8000/api/docs