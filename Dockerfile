FROM php:7.3-fpm

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libjpeg-dev\
    libpng-dev\
    libfreetype6-dev \
    libpq-dev \
    libicu-dev g++ \
    libxml2-dev \
    mariadb-client \
    crudini \
    fcgiwrap \
    libzip-dev

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/

RUN docker-php-ext-install pdo pdo_mysql zip gd intl mbstring bcmath sockets iconv mysqli soap

# PHP VARIABLES
ENV PHP_GLOBAL_PATH="/usr/local/etc/php/"
ENV PHP_GLOBAL_CONFIG_PATH="${PHP_GLOBAL_PATH}conf.d/"
ENV PHP_CONFIG_PATH="/var/php/config/"
ENV PHP_FPM_CONFIG="${PHP_CONFIG_PATH}php-fpm.conf"
ENV PHP_INI_CONFIG="${PHP_CONFIG_PATH}php.ini"
ENV CONFIG_GLOBAL_XDEBUG="${PHP_GLOBAL_CONFIG_PATH}docker-php-ext-xdebug.ini"
ENV CONFIG_XDEBUG="${PHP_CONFIG_PATH}xdebug.ini"
ENV PATH_XDEBUG_PROFILE="/var/xdebug/"
ENV PHP_INI_OPCACHE="${PHP_CONFIG_PATH}opcache.ini"

# PHP PM (512 Mb memory / 60 = 8)
ENV PHP_NAME=www
ENV PHP_PM=dynamic
ENV PHP_PM_MAX_CHILDREN=8
ENV PHP_PM_START_SERVERS=2
ENV PHP_PM_MIN_SPARE_SERVERS=2
ENV PHP_PM_MAX_SPARE_SERVERS=4
ENV PHP_PM_MAX_REQUESTS=500
ENV PHP_PM_PROCESS_IDLE_TIMEOUT=300
ENV COMPOSER_INSTALL_PARAMS="-a --no-dev"

# INSTALANDO MEMCACHED
RUN apt-get update && apt-get install -y libmemcached-dev \
 && pecl install memcached-3.1.3 \
 && docker-php-ext-enable memcached

# CONFIGURANDO TIMEZONE
RUN pecl install timezonedb \
 && echo "extension=timezonedb.so" > ${PHP_GLOBAL_CONFIG_PATH}00_timezone.ini

# INSTALANDO SUPERVISOR
RUN apt-get update && apt-get install -y supervisor

# CONFIGURAÇÃO PHP
COPY config/ ${PHP_CONFIG_PATH}
RUN ln -s "${PHP_GLOBAL_PATH}php.ini-production" "${PHP_GLOBAL_PATH}php.ini" \ 
 && ln -s "${PHP_CONFIG_PATH}php.ini" "${PHP_GLOBAL_CONFIG_PATH}00-docker-config.ini" \
 && chown -Rf www-data:www-data ${PHP_CONFIG_PATH}

# INSTALANDO APCU
RUN pecl install apcu \
 && docker-php-ext-enable apcu

# HABILITANDO OPCACHE
RUN docker-php-ext-install opcache \
 && ln -s "${PHP_CONFIG_PATH}opcache.ini" "${PHP_GLOBAL_CONFIG_PATH}00-opcache.ini"

# INSTALANDO DOCKERIZE
ENV DOCKERIZE_VERSION v0.6.1
RUN apt-get update && apt-get install -y wget \
 && wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
 && tar -C /usr/local/bin -xzvf dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
 && rm dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz

# INSTALANDO SUDO
RUN apt-get update && apt-get install -y sudo
RUN echo "www-data:www-data" | chpasswd && adduser www-data sudo
RUN echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers

ENV TZ=America/Fortaleza
ENV WWW=/var/www
ENV PUBLIC_HTML="${WWW}/public"
ENV COMPOSER_FOLDER="${PUBLIC_HTML}"
ENV DB_PORT=3306

ENV XDEBUG_PACKAGE=xdebug
ENV IONCUBE_PHP_VERSION=7.3
ENV IONCUBE_EXT_FOLDER=no-debug-non-zts-20180731

COPY sh/ /usr/local/bin/
RUN chmod +x /usr/local/bin/install-composer \
 && chmod +x /usr/local/bin/install-ioncube \
 && chmod +x /usr/local/bin/install-xdebug \
 && chmod +x /usr/local/bin/configure-php \
 && chmod +x /usr/local/bin/fpm-status \
 && chmod +x /usr/local/bin/start \
 && chmod +x /usr/local/bin/migrate-db \
 && chmod +x /usr/local/bin/composer-install \
 && chmod +x /usr/local/bin/composer-config \
 && chmod +x /usr/local/bin/start-php \
 && chmod +x /usr/local/bin/exec-cmd \
 && chmod +x /usr/local/bin/entrypoint-php \
 && chmod +x /usr/local/bin/composer \
 && chmod +x /usr/local/bin/php-utils \
 && chmod +x /usr/local/bin/run-as-www

COPY www/info.php $PUBLIC_HTML/index.php
RUN chown www-data:www-data /var/www/
RUN chown www-data:www-data $PUBLIC_HTML

RUN rm -rf /var/lib/apt/lists/*

WORKDIR $PUBLIC_HTML

EXPOSE 9000

HEALTHCHECK CMD fpm-status || exit 1

ENTRYPOINT ["entrypoint-php"]