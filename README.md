# MTA Server Web Manager

Este proyecto es un **panel de administración web** para un servidor **MTA: San Andreas**. Permite gestionar jugadores, configuraciones, procesos y visualizar logs del servidor en tiempo real.

## 🚀 Características

✅ **Inicio de sesión y registro de usuarios**
✅ **Control del servidor** (Iniciar/Detener `MTA Server.exe` y verificar estado)
✅ **Visualización de logs en tiempo real** (`server.log`)
✅ **Administración de jugadores** (Kick, Ban, Mute)
✅ **Edición de archivos de configuración** (`mtaserver.conf`, `local.conf`, `editor.conf`)
✅ **Interfaz moderna y responsiva** con Bootstrap 5

## 📂 Estructura del Proyecto

```
MTA-Web-Manager/
│── index.php               # Página de inicio (login/registro)
│── dashboard.php           # Panel principal del servidor
│── process.php             # Manejo de autenticación
│── logout.php              # Cerrar sesión
│── players_list.php        # Lista de jugadores conectados
│── actions.php             # Ejecuta acciones (Kick, Ban, Mute)
│── config_manager.php      # Editor de configuración
│── header.php              # Barra de navegación
│── check_status.php        # Verifica estado del servidor
│── start_server.php        # Iniciar servidor MTA
│── stop_server.php         # Detener servidor MTA
│── read_log.php            # Muestra los logs en tiempo real
│── db.php                  # Configuración de la base de datos
│── assets/                 # Archivos CSS, JS e imágenes
│── sql/                    # Archivos SQL para la base de datos
```

## 🔧 Instalación

### 1️⃣ Requisitos

- **Windows Server 2022 x64**
- **XAMPP** (Última versión) con Apache y MySQL activos
- **Servidor MTA:SA** configurado en `C:\Games\mta`

### 2️⃣ Clonar el repositorio
```sh
git clone https://github.com/tu-usuario/MTA-Web-Manager.git
cd MTA-Web-Manager
```

### 3️⃣ Configurar base de datos
1. **Importar el archivo SQL** en `sql/mta_database.sql`
2. Editar `db.php` con los datos de conexión:
```php
$host = "localhost";
$user = "mta";
$password = "tu-contraseña";
$database = "mta_database";
```

### 4️⃣ Configurar Apache
1. Mueve el contenido del repositorio a `C:\xampp\htdocs\mta`
2. Inicia **Apache y MySQL** en XAMPP

### 5️⃣ Acceder a la página
Abre un navegador y ve a:
```
http://localhost/mta/index.php
```

## ⚡ Uso

1️⃣ **Iniciar sesión o registrarse**
2️⃣ **Acceder al Dashboard** y verificar estado del servidor
3️⃣ **Administrar jugadores** desde `players_list.php`
4️⃣ **Editar archivos de configuración** en `config_manager.php`
5️⃣ **Ver logs del servidor** en tiempo real

## 📜 Licencia
Este proyecto está bajo la licencia **MIT**.

## 👤 Autor
Desarrollado por **[Tu Nombre]**

## 🌟 Contribuciones
¡Las contribuciones son bienvenidas! Si deseas mejorar el proyecto, haz un **Pull Request** en GitHub.
