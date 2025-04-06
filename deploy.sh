#!/bin/bash

echo " Deteniendo contenedores existentes..."
docker-compose down -v

echo " Limpiando directorios de cache..."
rm -rf vendor
rm -rf bootstrap/cache/*

echo " Construyendo las imágenes..."
DOCKER_BUILDKIT=0 docker-compose build --no-cache

echo " Levantando los contenedores..."
docker-compose up -d

echo " Esperando a que MySQL esté listo..."
sleep 30

echo " Configurando Git safe directory..."
docker-compose exec -T app git config --global --add safe.directory /var/www

echo " Verificando la instalación de dependencias..."
docker-compose exec -T app composer install --no-dev --no-scripts --prefer-dist --no-progress

echo " Optimizando el autoloader..."
docker-compose exec -T app composer dump-autoload --optimize

echo " Generando clave de la aplicación..."
docker-compose exec -T app php artisan key:generate

echo " Ejecutando migraciones..."
docker-compose exec -T app php artisan migrate --force

echo " Creando enlace simbólico para el storage..."
docker-compose exec -T app php artisan storage:link

echo " ¡Despliegue completado!"

# Mostrar estado de los contenedores
echo " Estado de los contenedores:"
docker-compose ps
