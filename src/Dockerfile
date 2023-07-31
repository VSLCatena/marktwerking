FROM php:8-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libonig-dev \
    curl && \
    docker-php-ext-install pdo_mysql mbstring zip && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

COPY html/ /var/www/html/
    
RUN mkdir /docker-entrypoint-initdb.d && \
    cp /var/www/html/data/marktwerking.structure.sql /docker-entrypoint-initdb.d/

USER www-data  

CMD ["apachectl", "-D", "FOREGROUND"]

VOLUME ["/docker-entrypoint-initdb.d"]
