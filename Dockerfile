FROM php:7.2-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

RUN apt-get update && apt-get install -y --allow-unauthenticated gnupg apt-transport-https 

RUN echo "deb https://deb.nodesource.com/node_8.x trusty main" > /etc/apt/sources.list.d/nodesource.list \
    wget --quiet -O --no-check-certificate - https://deb.nodesource.com/gpgkey/nodesource.gpg.key \ 
    apt-key add  nodesource.gpg.key

# Install dependencies
RUN apt-get update && apt-get install -y --allow-unauthenticated \
    build-essential \
    mysql-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    nodejs 

    

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
RUN chown -R www:www /var/www

RUN composer install
RUN cp .env.example .env
RUN npm i

# Change current user to www
USER root

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
