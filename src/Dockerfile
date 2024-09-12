FROM php:8-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libonig-dev \
    curl && \
    docker-php-ext-install pdo_mysql mbstring zip && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

COPY html/ /var/www/html/
ENV DEPLOY_ENV=production
RUN mkdir /docker-entrypoint-initdb.d && \
    cp /var/www/html/data/marktwerking.structure.sql /docker-entrypoint-initdb.d/ && \
    cp /usr/local/etc/php/php.ini-${DEPLOY_ENV} /usr/local/etc/php/php.ini && \
    mkdir /var/www/html/bar/tmp && chown www-data:root /var/www/html/bar/tmp && \
    sed -ri ' \
        s!^(\s*CustomLog)\s+\S+!\1 /proc/self/fd/1!g; \
        s!^(\s*ErrorLog)\s+\S+!\1 /proc/self/fd/2!g; \
        ' /usr/local/etc/php/php.ini

USER www-data

CMD ["apachectl", "-D", "FOREGROUND"]

VOLUME ["/docker-entrypoint-initdb.d"]
