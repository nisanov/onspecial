FROM php:7.4-fpm

ARG PSR_VERSION=1.0.1
ARG PHALCON_VERSION=4.1.0
ARG PHALCON_EXT_PATH=php7/64bits

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpcre3-dev \
    libicu-dev \
    libpng-dev \
    zlib1g-dev \
    libfreetype6-dev \
    unzip \
    git \
    chromium \
    vim

RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip
RUN docker-php-ext-configure gd --with-freetype
RUN docker-php-ext-install gd

RUN set -xe && \
        curl -LO https://github.com/jbboehr/php-psr/archive/v${PSR_VERSION}.tar.gz && \
        tar xzf ${PWD}/v${PSR_VERSION}.tar.gz && \
        curl -LO https://github.com/phalcon/cphalcon/archive/v${PHALCON_VERSION}.tar.gz && \
        tar xzf ${PWD}/v${PHALCON_VERSION}.tar.gz && \
        docker-php-ext-install pdo_mysql \
            opcache \
            intl && \
        docker-php-ext-install -j $(getconf _NPROCESSORS_ONLN) \
            ${PWD}/php-psr-${PSR_VERSION} \
            ${PWD}/cphalcon-${PHALCON_VERSION}/build/${PHALCON_EXT_PATH} \
        && \
        rm -r \
            ${PWD}/v${PSR_VERSION}.tar.gz \
            ${PWD}/php-psr-${PSR_VERSION} \
            ${PWD}/v${PHALCON_VERSION}.tar.gz \
            ${PWD}/cphalcon-${PHALCON_VERSION}

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install redis; \
    docker-php-ext-enable redis; \
    pecl install xdebug; \
    docker-php-ext-enable xdebug; \
    echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.discover_client_host=true" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.client_port=9005" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "#xdebug.log=/tmp/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    rm -rf /tmp/pear

WORKDIR /code
