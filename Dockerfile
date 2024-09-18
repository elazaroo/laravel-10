# Usa una imagen base con PHP y las extensiones necesarias
FROM php:8.1-fpm

# Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    git \
    unzip \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Instala extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install intl mbstring soap sodium zip

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Copia el código de la aplicación
COPY . .

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instala las dependencias de la aplicación
RUN composer install

# Expone el puerto en el que se ejecutará la aplicación
EXPOSE 9000

# Comando para iniciar PHP-FPM
CMD ["php-fpm"]
