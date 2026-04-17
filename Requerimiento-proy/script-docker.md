
# Detiene el contenedor conflictivo
docker stop laravel_asistencia

# Elimina el contenedor
docker rm laravel_asistencia

# Si quieres una limpieza total de cualquier rastro anterior de este proyecto, usa:
docker compose -f .devcontainer/docker-compose.yml down --volumes --remove-orphans

# coamndo devcontainer:
devcontainer up --workspace-folder . --remove-existing-container

docker rm -f laravel_asistencia
docker network prune -f

# dar permiso a las carpetas
ejecutar estos comandos:
1. sudo chown -R $USER:$USER /home/emercado/emercado_data/Cursos/2026/web-IA/control-asistencia
2. sudo chmod -R u+w /home/emercado/emercado_data/Cursos/2026/web-IA/control-asistencia

