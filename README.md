# 🎮 MTA Server Manager Panel PRO

Panel web moderno y responsivo para administrar un servidor MTA (Multi Theft Auto). Incluye múltiples herramientas útiles, control de cuentas, sistema de roles, carga de archivos y más, todo en un entorno seguro y visualmente profesional.

## 📦 Características Principales

- ✅ **Login seguro** con sesiones PHP.
- 📁 **Gestor de archivos ZIP/RAR** con barra de progreso y opciones de extracción/eliminación.
- 👥 **Gestión de usuarios MTA** (SQLite) desde el panel web.
- 🎮 **Asignación de roles y grupos ACL** editando `acl.xml`.
- 📈 **Gráficas de jugadores** con Chart.js.
- ⚙️ **Modo mantenimiento** para bloquear funciones temporalmente.
- 🌓 **Interruptor de modo claro/oscuro**.
- 🔐 **Soporte multiusuario con roles**: admin, moderador, visitante.
- 🔔 **Notificaciones en tiempo real** (simuladas y ampliables).
- 🌐 **Diseño con Bootstrap 5 + Bootstrap Icons**.
- 🧩 **Separación de opciones MTA y herramientas generales**.

---

## 📁 Estructura del Proyecto

📂 mta/
├── dashboard.php
├── login.php
├── logout.php
├── users.php
├── crear_cuenta.php
├── roles.php
├── upload_manager.php
├── includes/
│ ├── accounts_sqlite.class.php
│ └── header.php
├── mods/
│ └── deathmatch/
│ └── acl.xml
├── config/
│ └── config_discord.ini
├── scripts/
│ └── mta_log_monitor.py
├── Z:/Servidores/mta/ (directorio de trabajo principal)

yaml
Copiar
Editar

---

## ⚙️ Requisitos

- ✅ Servidor web local con PHP (XAMPP recomendado)
- ✅ PHP 7.4 o superior
- ✅ SQLite3 activado
- ✅ Bootstrap 5
- ✅ Chart.js
- ✅ Python 3 (solo para script de monitoreo opcional)
- ✅ Acceso al archivo `acl.xml` del servidor MTA

---

## 🛠️ Instalación

1. Clona este repositorio:
git clone https://github.com/tuusuario/mta-panel-pro.git

Abre XAMPP y activa Apache y SQLite.
Coloca el proyecto en htdocs.
Configura el archivo config_discord.ini si deseas monitoreo con Discord.
Ejecuta mta_log_monitor.py si necesitas notificaciones automáticas desde el log.

📤 Gestor de Archivos ZIP/RAR
Sube un archivo comprimido y realiza las siguientes acciones:

✔️ Extraer contenido

🗑️ Eliminar todo el directorio Z:/Servidores/mta/

❌ Borrar el archivo subido

Incluye barra de progreso de carga (AJAX).

🛡️ Roles y ACL
Edita acl.xml gráficamente desde el panel.

Asigna grupos a usuarios (ej. Admin, Moderator, etc.)

Verifica integridad del XML automáticamente.

🖥️ Scripts Python
Incluye un script para monitorear el log del servidor MTA y enviar mensajes a Discord vía webhook cuando:

🔄 El servidor inicia

🧑 Un jugador entra o sale

💬 Un jugador envía un mensaje por chat

👤 Multiusuario
Registro y login de usuarios

Restricción de acceso según el rol

Visualización de quién está logueado

🧪 Modo Mantenimiento
Coloca un archivo llamado maintenance.lock en el directorio raíz para activar el modo mantenimiento. Se mostrará una alerta en el panel y se desactivarán ciertas funciones.

📸 Capturas (opcional)
Agrega aquí capturas de pantalla para mostrar el dashboard, el gráfico de actividad, o el formulario de roles.

📄 Licencia
Este proyecto está bajo la licencia MIT.

🙌 Agradecimientos
Gracias a la comunidad MTA por su documentación y a Bootstrap, Chart.js, y todos los que colaboraron en este proyecto.
