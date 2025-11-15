# Babelong - Sistema de Aprendizaje de Chino Mandarín

## 🚀 Descripción

Babelong es una plataforma web diseñada para el aprendizaje de chino mandarín, construida con Symfony 7.3 y PHP 8.3.

## 📋 Características

### Niveles de Acceso

1. **Público** - Accesible sin autenticación
   - Landing page con información del proyecto
   - Página de login
   - Página de registro

2. **Usuario** - Requiere autenticación de usuario registrado
   - (Próximamente: área de aprendizaje, progreso, etc.)

3. **Administrador** - Requiere autenticación con rol ROLE_ADMIN
   - Panel de administración
   - Gestión de usuarios
   - Estadísticas de base de datos
   - Gestión de administradores

## 🔐 Credenciales de Acceso

### Administrador por defecto:
- **Email:** `admin@babelong.com`
- **Password:** `admin123`

## 🛠️ Tecnologías

- **Backend:** Symfony 7.3
- **PHP:** 8.3
- **Base de Datos:** MySQL/MariaDB
- **ORM:** Doctrine
- **Seguridad:** Symfony Security Component
- **Plantillas:** Twig

## 📦 Instalación

1. Clonar el repositorio:
```bash
git clone https://github.com/angelmoratilla/php_babelong.git
cd php_babelong/babelong
```

2. Instalar dependencias:
```bash
composer install
```

3. Configurar la base de datos en `.env`:
```
DATABASE_URL="mysql://usuario:password@host:3306/babelong"
```

4. Ejecutar migraciones:
```bash
php bin/console doctrine:migrations:migrate
```

5. Crear un administrador:
```bash
php bin/console app:create-admin
```

6. Iniciar el servidor:
```bash
symfony server:start
```

## 🗂️ Estructura del Proyecto

```
babelong/
├── config/              # Configuración de Symfony
├── migrations/          # Migraciones de base de datos
├── public/             # Archivos públicos
├── src/
│   ├── Command/        # Comandos de consola
│   ├── Controller/     # Controladores
│   │   ├── AdminController.php
│   │   ├── DatabaseController.php
│   │   ├── HomeController.php
│   │   └── LandingController.php
│   └── Entity/         # Entidades Doctrine
│       ├── Admin.php
│       └── User.php
├── templates/          # Plantillas Twig
│   ├── admin/         # Vistas de administración
│   ├── database/      # Vistas de BD
│   ├── home/          # Vistas del panel admin
│   └── landing/       # Vistas públicas
└── vendor/            # Dependencias

```

## 🔒 Rutas Protegidas

### Rutas Públicas
- `/` - Landing page
- `/login` - Inicio de sesión
- `/register` - Registro de usuarios

### Rutas de Administrador (requieren ROLE_ADMIN)
- `/admin` - Panel de administración
- `/admin/users` - Gestión de usuarios
- `/admin/admins` - Gestión de administradores
- `/admin/database-stats` - Estadísticas de BD
- `/admin/about` - Acerca de

## 📝 Comandos Útiles

### Crear un administrador
```bash
php bin/console app:create-admin
```

### Ver rutas disponibles
```bash
php bin/console debug:router
```

### Limpiar caché
```bash
php bin/console cache:clear
```

### Ejecutar migraciones
```bash
php bin/console doctrine:migrations:migrate
```

## 🗄️ Base de Datos

### Tabla `admin`
- `id` - ID autoincremental
- `username` - Nombre de usuario (único)
- `email` - Correo electrónico (único)
- `password` - Contraseña hasheada con bcrypt
- `active` - Estado activo/inactivo

### Tabla `fw_user`
- Tabla existente de usuarios del sistema
- Conectada mediante Doctrine ORM

## 🔄 Próximas Características

- [ ] Sistema completo de registro de usuarios
- [ ] Área de usuario con ejercicios de chino
- [ ] Sistema de progreso y estadísticas personales
- [ ] Gestión de vocabulario HSK
- [ ] Práctica de caracteres Hanzi
- [ ] Sistema de frases y conversaciones
- [ ] Tests y evaluaciones

## 👥 Contribuir

Este es un proyecto en desarrollo activo. Las contribuciones son bienvenidas.

## 📄 Licencia

Este proyecto está bajo licencia privada.

## 📧 Contacto

Desarrollador: Ángel Moratilla
GitHub: [@angelmoratilla](https://github.com/angelmoratilla)

---

**Estado del Proyecto:** 🟢 En Desarrollo Activo
**Última Actualización:** 15 de Noviembre de 2025
