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
    libonig-dev \
    libxslt-dev \
    libsodium-dev \
    && rm -rf /var/lib/apt/lists/*

# Instala extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install intl mbstring soap sodium zip

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Copia el archivo composer.json y composer.lock
COPY composer.json composer.lock ./

# Instala las dependencias de la aplicación
RUN composer install --no-dev --optimize-autoloader

# Copia el resto del código de la aplicación
COPY . .

# Establece permisos adecuados para los archivos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copia el archivo de entorno de producción
COPY .env.example .env

# Genera la clave de la aplicación
RUN php artisan key:generate

# Configura las variables de entorno necesarias
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV APP_KEY=base64:GENERATED_APP_KEY
ENV APP_URL=http://localhost

# Expone el puerto en el que se ejecutará la aplicación
EXPOSE 9000

# Comando para iniciar PHP-FPM
CMD ["php-fpm"]