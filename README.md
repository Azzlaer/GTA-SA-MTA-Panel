# GTA-SA-MTA-Panel

Panel web básico para administrar tu servidor **Multi Theft Auto: San Andreas (MTA)** usando **XAMPP** en Windows.

---

## Descripción

Este proyecto proporciona una interfaz web desarrollada en PHP/Hack para gestionar diversas tareas administrativas en tu servidor MTA (SA), facilitando acciones que normalmente requieren acceso directo al servidor o manipulación manual.

### Funcionalidades principales
- Gestión de cuentas de usuario (registro, edición, eliminación).
- Implementación de roles y permisos mediante ACL.
- Acceso y ejecución de comandos RCON.
- Visualización de estadísticas del servidor.
- Administración de archivos y logs (FTP integrado).
- Reinicio rápido del servidor y control de estado.
- Integración con Discord (bot básico).
- Otras utilidades como compresión de recursos, edición remota, etc.

---

## Requisitos

- **Servidor web local** con PHP (Recomendado: **XAMPP** en Windows).
- **Servidor MTA:SA** corriendo en la misma máquina o accesible remotamente.
- Extensiones PHP habilitadas: `mysqli`, `curl`, `zip`, entre otras necesarias.
- Configuración correcta de conexión RCON en `config_rcon.php`.

---

## Instalación

1. Clona o descarga este repositorio.
2. Copia la carpeta al directorio `htdocs` de XAMPP (por ejemplo: `C:\xampp\htdocs\mta-panel`).
3. Inicia Apache desde el panel de control de XAMPP.
4. Crea una base de datos MySQL (por ejemplo, `mta_panel`) y habilita un usuario con todos los permisos.
5. Configura `db.php` con los datos de conexión (host, usuario, contraseña, base de datos).
6. Ajusta `config_rcon.php` con los datos de tu servidor MTA (host, puerto, contraseña RCON).
7. (Opcional) Personaliza estilos en `config_css.ini`.
8. Accede al panel desde el navegador: `http://localhost/mta-panel/`.

---

## Uso

- Regístrate o entra con una cuenta existente.
- Crea usuarios y administra sus roles en la interfaz.
- Ejecuta comandos directos al servidor mediante la opción RCON.
- Navega y administra archivos del servidor a través del módulo FTP.
- Consulta estadísticas del servidor y monitorea su estado.
- Ejecuta tareas como reinicio, limpieza o instalación de recursos.

---

## Estructura del proyecto

| Carpeta / Archivo       | Descripción                                       |
|-------------------------|---------------------------------------------------|
| `index.php`             | Punto de entrada principal del panel              |
| `login.php` / `logout.php` | Manejadores de sesión y autenticación        |
| `users.php`, `roles.php`, `acl_editor.php` | Gestión de usuarios, roles y permisos |
| `rcon_panel.php`, `rcon_exec.php` | Panel y ejecución de comandos RCON     |
| `ftp_manager.php`, `ftp_*`         | Funciones para manejo de archivos vía FTP |
| `bot_discord.php`                   | Integración básica con bot de Discord    |
| `dashboard.php`, `estadisticas.php` | Vista principal y estadísticas del servidor |
| Otros (`*.php`, `*.ini`)           | Utilidades internas y configuración      |

---

## Contribuciones

¡Las contribuciones son bienvenidas! Puedes ayudar de las siguientes maneras:

- Reporta errores o problemas desde la sección **Issues**.
- Envía mejoras o nuevas funcionalidades mediante **Pull Requests**.
- Comparte ideas o reportes en la sección **Discussions** (según habilites esa sección).

---

## Licencia

Este proyecto está disponible bajo licencia **[especificar licencia aquí si existe]**.

---

## Créditos

Creado por **Azzlaer**, con foco en facilitar la administración de servidores MTA desde un entorno Windows con XAMPP.

---

### Notas adicionales

- Asegúrate de que tu servidor MTA esté configurado para permitir conexiones RCON desde el panel.
- Si tienes problemas con FTP, revisa la configuración de seguridad de Windows o la configuración de tu servidor FTP local.
- Puedes personalizar la apariencia editando directamente `config_css.ini` o los archivos de plantilla.

---

¡Listo! Este README debería brindar una visión clara del objetivo del proyecto, cómo instalarlo, configurarlo y usarlo. Si deseas adaptarlo más (por ejemplo, agregar secciones de FAQ, video tutorial, ejemplos de uso, o enlaces externos), solo dime y lo ajustamos juntos.
::contentReference[oaicite:0]{index=0}
