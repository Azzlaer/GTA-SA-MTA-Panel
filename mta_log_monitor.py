import time
import requests
import configparser

def read_config():
    config = configparser.ConfigParser()
    config.read("config_discord.php")
    return {
        "discord_webhook": config.get("SETTINGS", "discord_webhook", fallback=""),
        "server_start": config.get("SETTINGS", "server_start", fallback="Server started and is ready to accept connections!"),
        "server_join": config.get("SETTINGS", "server_join", fallback="{PLAYER} ({IP}) ha ingresado al servidor"),
        "server_leave": config.get("SETTINGS", "server_leave", fallback="{PLAYER} ha salido del servidor"),
        "server_chat": config.get("SETTINGS", "server_chat", fallback="{PLAYER}: {MESSAGE}"),
    }

def send_discord_message(webhook_url, message):
    if webhook_url:
        data = {"content": message}
        requests.post(webhook_url, json=data)
    else:
        print("[Error] Webhook de Discord no configurado.")

def monitor_log(log_file, config):
    with open(log_file, "r", encoding="utf-8") as file:
        file.seek(0, 2)  # Ir al final del archivo
        while True:
            line = file.readline()
            if not line:
                time.sleep(1)
                continue
            
            # Verificar si el servidor ha iniciado
            if config["server_start"] in line:
                send_discord_message(config["discord_webhook"], config["server_start"])
            
            # Verificar si un jugador ha ingresado
            if "JOIN:" in line:
                parts = line.split("JOIN:")[-1].strip().split(" ")
                if len(parts) > 2:
                    player_name = parts[0]
                    player_ip = parts[-1].replace("(IP:", "").replace(")", "")
                    join_message = config["server_join"].replace("{PLAYER}", player_name).replace("{IP}", player_ip)
                    send_discord_message(config["discord_webhook"], join_message)
            
            # Verificar si un jugador ha salido
            if "QUIT:" in line:
                parts = line.split("QUIT:")[-1].strip().split(" ")
                if len(parts) > 1:
                    player_name = parts[0]
                    leave_message = config["server_leave"].replace("{PLAYER}", player_name)
                    send_discord_message(config["discord_webhook"], leave_message)
            
            # Verificar si un jugador ha enviado un mensaje en el chat
            if "CHAT:" in line:
                parts = line.split("CHAT:")[-1].strip().split(": ", 1)
                if len(parts) == 2:
                    player_name = parts[0]
                    message_text = parts[1]
                    chat_message = config["server_chat"].replace("{PLAYER}", player_name).replace("{MESSAGE}", message_text)
                    send_discord_message(config["discord_webhook"], chat_message)

if __name__ == "__main__":
    log_path = "C:/Games/mta/mods/deathmatch/logs/server.log"
    config = read_config()
    monitor_log(log_path, config)