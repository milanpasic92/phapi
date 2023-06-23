FROM debian:10.8-slim

RUN apt update -y
RUN apt-get install -y software-properties-common lsb-release ca-certificates \
    apt-transport-https wget gnupg2 zip unzip curl

#add php repositories
RUN wget -O - https://packages.sury.org/php/apt.gpg | apt-key add -
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list
RUN curl -s "https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh" | bash

ARG PHP_VERSION=7.4

RUN apt install -y apache2 libxml2-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
        php${PHP_VERSION} default-mysql-client libonig-dev cron libicu-dev php${PHP_VERSION}-intl \
        php${PHP_VERSION}-mysql php${PHP_VERSION}-curl php${PHP_VERSION}-zip php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-json php${PHP_VERSION}-dev php${PHP_VERSION}-psr php${PHP_VERSION}-xdebug \
        php${PHP_VERSION}-phalcon4

RUN echo "xdebug.mode=debug\n \
    xdebug.client_port=9000\n \
    xdebug.discover_client_host=1\n" >> /etc/php/${PHP_VERSION}/mods-available/xdebug.ini

#add ini entries
RUN echo "upload_tmp_dir=/tmp_uploads\n \
    open_basedir=/app:/tmp:/tmp_uploads\n \
    session.gc_maxlifetime=2592000\n" >> /etc/php/${PHP_VERSION}/apache2/php.ini

#apache modes enabling
RUN a2enmod rewrite ssl

WORKDIR /app

#composer install
COPY composer.* /app/
COPY composer.phar /app
RUN php composer.phar install --no-dev --prefer-dist --optimize-autoloader && \
    php composer.phar clear-cache

#prepare scripts
COPY docker/wait-for-it.sh /usr/local/bin
COPY docker/run.sh /usr/local/bin

RUN ["chmod", "+x", "/usr/local/bin/wait-for-it.sh"]
RUN ["chmod", "+x", "/usr/local/bin/run.sh"]

COPY . /app
COPY docker/app.conf /etc/apache2/sites-available/000-default.conf

CMD ["run.sh"]

# docker build --no-cache -f docker/Dockerfile -t milanpasa/phapi .
