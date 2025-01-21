#!/bin/bash

# Actualizar paquetes e instalar dependencias necesarias
apt-get update && apt-get install -y libssl1.0.0

# Continuar con la instalación de dependencias PHP usando Composer
composer install --no-dev --optimize-autoloader
