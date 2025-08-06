# Babelong - Proyecto Symfony

Un proyecto Symfony moderno y funcional creado con PHP 8.3 y Symfony 7.3.

## 🚀 Características incluidas

- **Symfony 7.3** - Framework PHP moderno y robusto
- **PHP 8.3** - Última versión de PHP con todas las extensiones necesarias
- **Twig Templates** - Motor de plantillas moderno y seguro
- **Symfony Forms** - Sistema robusto para crear formularios
- **Validator** - Validación de datos potente y flexible
- **Asset Component** - Gestión optimizada de recursos estáticos

## 📋 Requisitos del sistema

- PHP 8.3 o superior
- Composer 2.0+
- Extensiones PHP requeridas:
  - ext-xml
  - ext-mbstring
  - ext-zip
  - ext-intl
  - ext-curl
  - ext-gd

## 🛠 Instalación y configuración

### 1. Instalar dependencias
```bash
cd babelong
composer install
```

### 2. Configurar el entorno
```bash
# Copiar el archivo de configuración de ejemplo
cp .env .env.local

# Editar las variables de entorno según tu configuración
# DATABASE_URL, APP_ENV, etc.
```

### 3. Iniciar el servidor de desarrollo
```bash
symfony server:start
```

O usando PHP nativo:
```bash
php -S localhost:8000 -t public/
```

## 📁 Estructura del proyecto

```
babelong/
├── bin/                 # Ejecutables (console)
├── config/              # Archivos de configuración
├── public/              # Directorio público (punto de entrada)
├── src/                 # Código fuente de la aplicación
│   └── Controller/      # Controladores
├── templates/           # Plantillas Twig
├── var/                 # Archivos temporales (cache, logs)
└── vendor/              # Dependencias de Composer
```

## 🎯 Primeros pasos

1. **Ver la página principal**: `http://localhost:8001`
2. **Ver información del proyecto**: `http://localhost:8001/about`
3. **Crear un nuevo controlador**:
   ```bash
   symfony console make:controller
   ```

## 🔧 Comandos útiles

```bash
# Limpiar caché
symfony console cache:clear

# Ver rutas disponibles
symfony console debug:router

# Crear un controlador
symfony console make:controller

# Crear una entidad (requiere Doctrine)
symfony console make:entity

# Ver estado del servidor
symfony server:status

# Parar el servidor
symfony server:stop
```

## 📚 Documentación

- [Documentación oficial de Symfony](https://symfony.com/doc/current/index.html)
- [Guía de inicio rápido](https://symfony.com/doc/current/page_creation.html)
- [Componente Twig](https://twig.symfony.com/doc/3.x/)

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

---

¡Desarrollado con ❤️ usando Symfony!
