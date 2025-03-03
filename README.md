# Proyecto: Gestor FTP Online y Administrador de Servidor MTA

## Descripción
Este proyecto es un **gestor FTP online** con funcionalidades avanzadas y un **administrador de servidor MTA**, permitiendo a los usuarios:

- **Gestión de archivos vía FTP** (subir, eliminar, renombrar, editar archivos de texto y navegar entre carpetas).
- **Administración del servidor MTA** (iniciar, detener el servidor, ver logs en tiempo real y enviar eventos a Discord).
- **Configuración de fondo dinámico** (cambiar entre imagen o video como fondo de la web).
- **Monitorización de MySQL** (ver estado y credenciales de conexión).

## Tecnologías Utilizadas
- **PHP 8.2** (Backend y gestión del servidor FTP/MTA)
- **Bootstrap 5.1** (Interfaz responsiva y moderna)
- **MySQL** (Gestión de datos del sistema y usuarios)
- **Python** (Monitor de logs de MTA y webhook de Discord)
- **FTP** (Manejo de archivos en servidores remotos)
- **XAMPP en Windows Server 2022**

---

## Instalación y Configuración
### 1️⃣ Requisitos Previos
- **Servidor Web Apache** con PHP 8.2 (Recomendado XAMPP en Windows Server 2022).
- **Servidor MySQL en localhost**.
- **Servidor FTP activo y accesible**.
- **Servidor MTA:SA configurado** en `C:/Games/mta/`.

### 2️⃣ Clonar el Repositorio
```bash
git clone https://github.com/tu-usuario/tu-repositorio.git
cd tu-repositorio
```

### 3️⃣ Configurar Base de Datos
Ejecuta las siguientes **queries SQL** en tu servidor MySQL:
```sql
CREATE DATABASE latinbat_mta;
USE latinbat_mta;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);
```
Edita el archivo **db.php** con las credenciales correctas:
```php
$servername = "localhost";
$username = "mta";
$password = "yi[JHe*r4Kton*!M";
$dbname = "latinbat_mta";
$port = 3306;
```

### 4️⃣ Configurar FTP
Edita el archivo **ftp_manager.php** con las credenciales de tu servidor FTP:
```php
$ftp_server = "tu-servidor-ftp.com";
$ftp_user = "usuario_ftp";
$ftp_pass = "contraseña_ftp";
```

### 5️⃣ Configurar Monitor de Logs y Discord Bot
Modifica **config_discord.ini**:
```ini
[SETTINGS]
discord_webhook="https://discord.com/api/webhooks/TU_WEBHOOK"
server_start="Servidor iniciado!"
server_join="El jugador {PLAYER} ({IP}) ha ingresado."
server_leave="El jugador {PLAYER} ha salido."
server_chat="{PLAYER}: {MESSAGE}"
```
Ejecuta el **script Python** para monitorear logs:
```bash
python mta_log_monitor.py
```

---

## Funcionalidades
### 📂 Gestor FTP Online
✅ Navegar entre carpetas 🚀  
✅ Subir archivos 📤  
✅ Descargar archivos 📥  
✅ Eliminar y renombrar archivos/carpeta 🗑️  
✅ Editar archivos de texto en línea ✍️

### 🎮 Administrador de Servidor MTA
✅ Iniciar y detener el servidor 🟢🔴  
✅ Monitorear logs en tiempo real 📜  
✅ Enviar eventos a Discord 📢  
✅ Configuración de archivos MTA en línea ⚙️

### 🖼️ Configuración de Fondo de Pantalla
✅ Seleccionar entre **imagen o video** como fondo 🎬  
✅ Subir nuevos fondos y previsualizarlos 🌄

### 💾 Monitorización de MySQL
✅ Ver estado de conexión MySQL ✅  
✅ Mostrar credenciales de base de datos 🔐

---

## 📌 Créditos y Contacto
Desarrollado por **[Azzlaer]** | GitHub: [Tu Repositorio](https://github.com/Azzlaer) 🚀

Si tienes dudas o sugerencias, ¡abre un issue o contáctame! 😊
