FROM php:7.4-fpm-alpine

ARG WEBSERVER_PORT 
ARG APP_PORT

# Set working dir
WORKDIR /var/www/html

# Install required packages
RUN apk update && apk add \
    build-base \
    freetype-dev \
    zip \
    vim \
    unzip \
    git \
    curl \
    nano \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    shadow 
    # php7-pecl-xdebug

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl
# RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Install PHP Xdebug
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug-3.0.2 \
    && docker-php-ext-enable xdebug

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node  and peppeteer's required packages
RUN apk add --no-cache \
      chromium \
      nss \
      freetype \
      harfbuzz \
      ca-certificates \
      ttf-freefont \
      nodejs \
      npm \
      nano

# Add user for laravel application
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/html

# Change current user to www
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE ${WEBSERVER_PORT}
EXPOSE ${APP_PORT}
CMD ["php-fpm"]
