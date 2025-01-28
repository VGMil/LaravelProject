FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
  git \
  unzip \
  libzip-dev \
  libonig-dev \
  libxml2-dev \
  libpng-dev \
  libjpeg-dev \
  libfreetype6-dev \
  && docker-php-ext-install pdo_mysql zip mbstring exif pcntl bcmath gd \
  && apt-get clean && rm -rf /var/lib/apt/lists/*


WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /user/bin/composer
RUN chown -R www-data:www-data /var/www/html

