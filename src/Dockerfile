FROM php:8.3-fpm as app

WORKDIR /usr/src

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libc6 \
    zip \
    unzip \
    supervisor \
    default-mysql-client \
    nano \
    procps

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring pcntl bcmath gd zip

#RUN pecl install redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./src/composer*.json /usr/src/
COPY ./deployment/config/php-fpm/php-prod.ini /usr/local/etc/php/conf.d/php.ini
COPY ./deployment/config/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./src/wait-for-it.sh /usr/src/

COPY ./src .

RUN composer install --no-scripts

RUN php artisan storage:link && \
    cp .env.example .env && \
    php artisan key:generate && \
    mkdir "storage/logs" && \
    mkdir "storage/tntdb" && \
    chmod -R 775 ./storage ./bootstrap/cache && \
    chown -R www-data:root /usr/src


#USER webappuser

#FROM backend AS worker
#COPY ./deployment/config/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisor.conf
#CMD ["/bin/sh", "-c", "supervisord -c /etc/supervisor/conf.d/supervisor.conf"]
#
FROM app AS scheduler
CMD ["/bin/sh", "-c", "nice -n 10 sleep 60 && php /usr/src/artisan schedule:run --verbose --no-interaction"]
