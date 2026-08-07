FROM node:20-alpine AS node-builder

WORKDIR /app

# Copy configuration files first for better caching
COPY package.json package-lock.json* vite.config.js ./

RUN npm ci --silent

# Copy all source files needed for the frontend build
COPY . .

RUN npm run build

# --- Base Stage ---
FROM php:8.4-fpm AS base

# Set global Composer timeout to infinity for large packages
ENV COMPOSER_PROCESS_TIMEOUT=0

WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  libzip-dev \
  libpng-dev \
  libjpeg-dev \
  libwebp-dev \
  libavif-dev \
  libfreetype6-dev \
  libonig-dev \
  libicu-dev \
  mariadb-client \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-avif \
  && docker-php-ext-install \
  pdo_mysql \
  mbstring \
  zip \
  exif \
  intl \
  opcache \
  pcntl \
  bcmath \
  gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create a non-root user for Laravel
RUN useradd -G www-data,root -u 1000 -d /home/laravel laravel \
  && mkdir -p /home/laravel/.composer \
  && chown -R laravel:laravel /home/laravel

# --- Development Stage ---
FROM base AS development

# Install Node.js for development
RUN apt-get update && apt-get install -y \
  nodejs \
  npm \
  && rm -rf /var/lib/apt/lists/*

COPY composer.json composer.lock ./

RUN composer install --no-interaction --prefer-dist --no-scripts --no-autoloader

COPY . .

RUN composer dump-autoload -o --no-scripts \
  && composer install --no-interaction --prefer-dist --no-scripts

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
  && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]

# --- Production Stage ---
FROM base AS production

# Optimizing for production
ENV APP_ENV=production
ENV APP_DEBUG=false

COPY composer.json composer.lock ./

RUN composer install --no-interaction --prefer-dist --no-scripts --no-autoloader --no-dev

COPY . .

# Copy compiled assets from node-builder
COPY --from=node-builder /app/public/build ./public/build

RUN composer dump-autoload -o --no-scripts \
  && composer install --no-interaction --prefer-dist --no-dev --no-scripts

# Setup entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Ensure storage directories exist and have proper permissions
RUN mkdir -p /var/www/html/storage/logs \
  && mkdir -p /var/www/html/storage/framework/cache/data \
  && mkdir -p /var/www/html/storage/framework/sessions \
  && mkdir -p /var/www/html/storage/framework/views \
  && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
  && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
