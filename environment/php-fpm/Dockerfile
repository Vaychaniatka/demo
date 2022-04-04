FROM php:8.0-fpm

WORKDIR /app

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y \
    librabbitmq-dev \
    libpq-dev \
    libssh-dev \
    libxslt1-dev \
    git \
    unzip && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install pdo pdo_pgsql pgsql bcmath sockets xsl && \
    pecl install amqp && \
    docker-php-ext-enable amqp && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    mkdir -p var/cache var/log && \
    chown -R www-data var/* && \
    chmod 777 -R var/*

EXPOSE 9000

CMD ["php-fpm"]