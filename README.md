# Sistema de Control de Inventario y Resguardo de Equipos Tecnológicos

Sistema empresarial profesional para la administración de laptops, equipos de cómputo y dispositivos tecnológicos asignados a empleados.

## 🚀 Tecnologías

- **Backend:** Laravel 11
- **Frontend:** Blade + Bootstrap 5
- **Base de datos:** PostgreSQL
- **ORM:** Eloquent
- **Autenticación:** Laravel Breeze
- **PDFs:** DomPDF
- **Permisos:** Spatie Laravel Permission
- **Arquitectura:** MVC Profesional

## 📋 Requisitos del Sistema

- PHP >= 8.2
- Composer >= 2.0
- PostgreSQL >= 14
- Node.js >= 18 (opcional, para assets)
- Git

## 🔧 Instalación en Windows con XAMPP

### Paso 1: Instalar PostgreSQL

1. Descargar PostgreSQL desde: https://www.postgresql.org/download/windows/
2. Ejecutar el instalador y seguir los pasos
3. Recordar la contraseña del usuario `postgres`
4. Puerto por defecto: 5432

### Paso 2: Crear la Base de Datos

Abrir **pgAdmin** o usar la terminal de PostgreSQL:

```sql
-- Conectar como postgres
psql -U postgres

-- Crear la base de datos
CREATE DATABASE inventory_system;

-- Verificar
\l
```

O usando pgAdmin:
1. Click derecho en "Databases"
2. Create > Database
3. Nombre: `inventory_system`
4. Save

### Paso 3: Configurar PHP para PostgreSQL

1. Abrir `C:\xampp\php\php.ini`
2. Buscar y descomentar (quitar el `;`):
```ini
extension=pdo_pgsql
extension=pgsql
```
3. Reiniciar Apache en XAMPP

### Paso 4: Clonar/Copiar el Proyecto

```bash
# Navegar a la carpeta de proyectos
cd C:\xampp\htdocs

# Copiar el proyecto
# O clonar desde repositorio si está disponible
```

### Paso 5: Instalar Dependencias

Abrir terminal (CMD o PowerShell) en la carpeta del proyecto:

```bash
cd C:\xampp\htdocs\inventory-system

# Instalar dependencias de PHP
composer install

# Copiar archivo de configuración
copy .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### Paso 6: Configurar Variables de Entorno

Editar el archivo `.env`:

```env
APP_NAME="Sistema de Inventario y Resguardo"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=inventory_system
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña_de_postgres

# Configuración de la empresa
COMPANY_NAME="Mi Empresa S.A. de C.V."
COMPANY_ADDRESS="Calle Principal #123, Col. Centro"
COMPANY_PHONE="+52 (55) 1234-5678"
COMPANY_EMAIL="contacto@baglass.com"
COMPANY_RFC="ABC123456789"
```

### Paso 7: Ejecutar Migraciones y Seeders

```bash
# Crear tablas en la base de datos
php artisan migrate

# Cargar datos de prueba
php artisan db:seed

# O todo junto
php artisan migrate:fresh --seed
```

### Paso 8: Crear Enlace de Almacenamiento

```bash
php artisan storage:link
```

### Paso 9: Iniciar el Servidor

```bash
php artisan serve
```

El sistema estará disponible en: **http://localhost:8000**

## 👤 Usuarios de Prueba

| Rol | Email | Contraseña |
|-----|-------|------------|
| Administrador | admin@baglass.com | Admin123! |
| Supervisor | carlos.rodriguez@baglass.com | Supervisor123! |
| Supervisor RH | laura.martinez@baglass.com | Supervisor123! |
| Empleado | miguel.hernandez@baglass.com | Empleado123! |

## 📁 Estructura del Proyecto

```
inventory-system/
├── app/
│   ├── Http/
│   │   └── Controllers/       # Controladores
│   ├── Models/                # Modelos Eloquent
│   └── Providers/             # Proveedores de servicios
├── config/                    # Configuraciones
├── database/
│   ├── migrations/            # Migraciones de BD
│   └── seeders/               # Datos de prueba
├── public/                    # Archivos públicos
├── resources/
│   └── views/                 # Vistas Blade
│       ├── layouts/           # Layouts principales
│       ├── equipment/         # Vistas de equipos
│       ├── employees/         # Vistas de empleados
│       ├── assignments/       # Vistas de asignaciones
│       ├── pdf/               # Plantillas PDF
│       └── auth/              # Autenticación
├── routes/
│   ├── web.php                # Rutas web
│   └── auth.php               # Rutas de autenticación
├── storage/                   # Almacenamiento
├── .env.example               # Variables de entorno ejemplo
├── composer.json              # Dependencias PHP
└── README.md                  # Este archivo
```

## 🔐 Roles y Permisos

### Administrador
- Acceso total al sistema
- Gestión de usuarios y roles
- Configuración del sistema
- Auditoría completa

### Supervisor / RH / Sistemas
- Ver y gestionar equipos
- Asignar y transferir equipos
- Generar responsivas PDF
- Ver historial completo
- Generar reportes

### Empleado
- Ver sus equipos asignados
- Descargar sus responsivas
- Ver su historial

## 📊 Módulos del Sistema

1. **Dashboard** - Panel principal con estadísticas
2. **Equipos** - Gestión completa del inventario
3. **Empleados** - Administración de personal
4. **Asignaciones** - Control de entregas y devoluciones
5. **Historial** - Trazabilidad completa
6. **Mantenimiento** - Registro de reparaciones
7. **Reportes** - Informes y exportaciones
8. **Responsivas PDF** - Generación automática
9. **Catálogos** - Departamentos, ubicaciones, marcas
10. **Configuración** - Parámetros del sistema
11. **Auditoría** - Registro de actividades

## 🛠️ Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Regenerar autoload
composer dump-autoload

# Ver rutas
php artisan route:list

# Crear controlador
php artisan make:controller NombreController

# Crear modelo con migración
php artisan make:model Nombre -m

# Rollback de migraciones
php artisan migrate:rollback

# Estado de migraciones
php artisan migrate:status
```

## 📝 Características Principales

- ✅ Inventario completo de equipos tecnológicos
- ✅ Asignación y reasignación de equipos
- ✅ Historial de movimientos permanente
- ✅ Generación automática de responsivas PDF
- ✅ Control de garantías
- ✅ Registro de mantenimientos
- ✅ Reportes y exportaciones
- ✅ Sistema de roles y permisos
- ✅ Auditoría de acciones
- ✅ Diseño responsive y moderno
- ✅ Código QR en responsivas

## 🔒 Seguridad

- Autenticación con Laravel Breeze
- Roles y permisos con Spatie
- Protección CSRF
- Validación de formularios
- Encriptación de contraseñas (bcrypt)
- Auditoría de acciones

## 📄 Licencia

Este proyecto es de uso interno empresarial.

## 👨‍💻 Soporte

Para soporte técnico, contactar al área de TI.

---

**Sistema de Control de Inventario y Resguardo de Equipos Tecnológicos**  
Desarrollado con Laravel 11 + Bootstrap 5 + PostgreSQL
