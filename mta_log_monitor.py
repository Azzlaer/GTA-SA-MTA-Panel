import time
import requests
import os
import configparser

# Ruta de configuración y log
CONFIG_FILE_PATH = "config_discord.ini"
LOG_FILE_PATH = "C:/Games/mta/mods/deathmatch/logs/server.log"

# Verificar si config_discord.ini existe
if not os.path.exists(CONFIG_FILE_PATH):
    print("Error: El archivo config_discord.ini no existe en el directorio.")
    exit()

# Cargar configuración
config = configparser.ConfigParser()
config.read(CONFIG_FILE_PATH, encoding="utf-8")

DISCORD_WEBHOOK = config.get("SETTINGS", "discord_webhook", fallback=None)
SERVER_START_MSG = config.get("SETTINGS", "server_start", fallback="Servidor iniciado!")
SERVER_JOIN_MSG = config.get("SETTINGS", "server_join", fallback="El jugador {PLAYER} ({IP}) ha ingresado.")
SERVER_LEAVE_MSG = config.get("SETTINGS", "server_leave", fallback="El jugador {PLAYER} ha salido.")
SERVER_CHAT_MSG = config.get("SETTINGS", "server_chat", fallback="{PLAYER}: {MESSAGE}")

# Verificar si el archivo de log existe
if not os.path.exists(LOG_FILE_PATH):
    print("Error: El archivo de log no existe en la ruta especificada.")
    exit()

# Verificar la disponibilidad del webhook de Discord
def check_discord_webhook():
    try:
        response = requests.get(DISCORD_WEBHOOK)
        if response.status_code == 200:
            print("[WEBHOOK] Webhook de Discord está operativo.")
        else:
            print("[ERROR] Webhook de Discord no está accesible. Código:", response.status_code)
            exit()
    except requests.exceptions.RequestException as e:
        print("[ERROR] No se pudo conectar al Webhook de Discord:", e)
        exit()

def send_to_discord(message):
    if DISCORD_WEBHOOK:
        payload = {"content": message}
        try:
            requests.post(DISCORD_WEBHOOK, json=payload)
        except Exception as e:
            print(f"Error al enviar mensaje a Discord: {e}")

def monitor_log():
    with open(LOG_FILE_PATH, "r", encoding="utf-8") as file:
        file.seek(0, os.SEEK_END)  # Ir al final del archivo
        while True:
            line = file.readline()
            if not line:
                time.sleep(1)
                continue
            
            line = line.strip()
            
            if "Server started and is ready to accept connections!" in line:
                send_to_discord(SERVER_START_MSG)
            
            elif "JOIN:" in line:
                parts = line.split("JOIN: ")
                if len(parts) > 1:
                    player_info = parts[1].split(" ")
                    player_name = player_info[0]
                    player_ip = player_info[-1].replace("(IP: ", "").replace(")", "")
                    send_to_discord(SERVER_JOIN_MSG.replace("{PLAYER}", player_name).replace("{IP}", player_ip))
            
            elif "QUIT:" in line:
                parts = line.split("QUIT: ")
                if len(parts) > 1:
                    player_name = parts[1].split(" ")[0]
                    send_to_discord(SERVER_LEAVE_MSG.replace("{PLAYER}", player_name))
            
            elif "CHAT:" in line:
                parts = line.split("CHAT: ")
                if len(parts) > 1:
                    chat_parts = parts[1].split(": ", 1)
                    if len(chat_parts) > 1:
                        player_name, message = chat_parts
                        send_to_discord(SERVER_CHAT_MSG.replace("{PLAYER}", player_name).replace("{MESSAGE}", message))

if __name__ == "__main__":
    try:
        print("[LOG MONITOR] Verificando dependencias...")
        check_discord_webhook()
        print("[LOG MONITOR] Iniciando monitoreo del servidor MTA...")
        monitor_log()
    except Exception as e:
        with open("error_log.txt", "w", encoding="utf-8") as f:
            f.write(f"Error detectado: {str(e)}\n")

