FROM php:8-apache
# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libonig-dev \
    curl \ 
&& apt-get clean && rm -rf /var/lib/apt/lists/* \
&& docker-php-ext-install pdo_mysql mbstring zip

#Set user
USER www-data  

#Start app // disable app exit.
CMD ["apachectl", "-D", "FOREGROUND"]
