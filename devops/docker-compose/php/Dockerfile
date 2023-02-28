FROM ubuntu:20.04

ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y software-properties-common
RUN add-apt-repository ppa:ondrej/php
RUN apt-get update && apt-get install -y php8.1 \
        php-pear \
        php8.1-bcmath \
        php8.1-cli \
        php8.1-curl \
        php8.1-pcov \
        php8.1-dev \
        php8.1-fpm \
        php8.1-gd \
        php8.1-igbinary \
        php8.1-imagick \
        php8.1-mbstring \
        php8.1-mcrypt \
        php8.1-pgsql \
        php8.1-opcache \
        php8.1-redis \
        php8.1-xml \
        php8.1-zip \
        php8.1-xdebug \
        php8.1-intl \
        git \
        wget \
        curl \
        unzip && apt-get autoremove && apt-get clean
### Symfony cli
RUN bash -c "wget -q https://get.symfony.com/cli/installer -O - | bash" && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
RUN ln -nfs /usr/share/zoneinfo/Europe/Paris /etc//localtime && /usr/sbin/dpkg-reconfigure -f noninteractive tzdata
### Configurations
COPY www.conf /etc/php/8.1/fpm/pool.d/www.conf
COPY php-fpm.conf /etc/php/8.1/fpm/php-fpm.conf
COPY xdebug.ini /etc/php/8.1/mods-available/xdebug.ini
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY 00-aliases.sh /root/.bash_aliases
RUN sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 10M/' /etc/php/8.1/fpm/php.ini
RUN sed -i 's/post_max_size = 8M/post_max_size = 10M/' /etc/php/8.1/fpm/php.ini
RUN mkdir /var/run/php/ && chown www-data: /var/run/php
WORKDIR /var/www
EXPOSE 9000
### Installation
CMD composer install \
    && /usr/sbin/php-fpm8.1 -F
