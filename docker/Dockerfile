FROM debian:10.8

RUN apt update -y
RUN apt-get install -y software-properties-common lsb-release ca-certificates apt-transport-https \
        wget gnupg2 zip unzip

ARG PHP_VERSION=7.4

#add php repositories
RUN wget -O - https://packages.sury.org/php/apt.gpg | apt-key add -
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list

RUN apt update -y

#install deps
RUN apt install -y \
    apache2 \
    php${PHP_VERSION} \
    libxml2-dev \
    curl \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    default-mysql-client \
    libonig-dev \
    cron \
    libicu-dev

#install extensions
RUN apt-get install -y php${PHP_VERSION}-intl \
    php${PHP_VERSION}-mysql \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-zip \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-json \
    php${PHP_VERSION}-dev \
    php${PHP_VERSION}-psr

#install xdebug
RUN apt-get install -y php${PHP_VERSION}-xdebug
RUN echo "xdebug.mode=debug" >> /etc/php/${PHP_VERSION}/mods-available/xdebug.ini
RUN echo 'xdebug.client_port=9000' >> /etc/php/${PHP_VERSION}/mods-available/xdebug.ini
RUN echo 'xdebug.discover_client_host=1' >> /etc/php/${PHP_VERSION}/mods-available/xdebug.ini


#install phalcon4
RUN curl -s "https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh" | bash
RUN apt-get install -y php${PHP_VERSION}-phalcon4

#add ini entries
RUN echo 'upload_tmp_dir=/tmp_uploads' >> /etc/php/${PHP_VERSION}/apache2/php.ini
RUN echo 'open_basedir=/app:/tmp:/tmp_uploads' >> /etc/php/${PHP_VERSION}/apache2/php.ini
RUN echo 'session.gc_maxlifetime=2592000' >> /etc/php/${PHP_VERSION}/apache2/php.ini

#apache modes enabling
RUN a2enmod rewrite
RUN a2enmod ssl

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
