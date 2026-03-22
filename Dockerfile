FROM php:8.2-apache

#Instalación de dependencias, entorno y posterior limipieza de caché
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libsqlite3-dev \
    nodejs \
    npm \
    docker.io

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

#Instalación de extensiones de PHP, configuración de apache y directorio de aplicación
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd
RUN a2enmod rewrite
RUN usermod -aG root www-data
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . /var/www/html

# Instalación de dependencias de PHP (Laravel)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Instalación de dependencias de Node.js y construcción de assets con Vite
RUN npm install && npm run build

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache
EXPOSE 80