FROM php:7.2-apache

RUN curl -OL https://github.com/composer/composer/releases/download/1.7.2/composer.phar && \
mv composer.phar /usr/local/bin/composer && \
chmod +x /usr/local/bin/composer

RUN apt-get update 
RUN apt-get -y install git
# kzlib1g-dev \
# libzip-dev

RUN a2enmod rewrite

RUN apt-get install -y --no-install-recommends \
    	zip \
        curl \
        libmemcached-dev \
        libz-dev \
        libpq-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libssl-dev \
        libmcrypt-dev


# Install the PHP pdo_mysql extention
RUN docker-php-ext-install pdo_mysql

# Install the PHP pdo_pgsql extention
RUN docker-php-ext-install pdo_pgsql

#####################################
# gd:
#####################################

# Install the PHP gd library
RUN docker-php-ext-configure gd \
        # --enable-gd-native-ttf \
        --with-jpeg-dir=/usr/lib \
        --with-freetype-dir=/usr/include/freetype2 && \
    docker-php-ext-install gd

RUN docker-php-ext-install zip

RUN apt-get install -y zlib1g-dev libicu-dev g++ \
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl

#CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]