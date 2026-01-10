FROM php:8.2-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    gnupg

# Instalar extensões do PHP (ADICIONADO: sockets e bcmath)
RUN docker-php-ext-install pdo_pgsql zip sockets bcmath

# Instalar Redis
RUN pecl install redis && docker-php-ext-enable redis

# Pegar o Composer mais recente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Agora o composer install vai funcionar
RUN composer install --no-interaction --optimize-autoloader

# Ajustar permissões para o Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 8000