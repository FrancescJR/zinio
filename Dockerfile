FROM php:7.4-cli

#pretty image we just need PHP installed.


#install php extension for symfony to work
WORKDIR /app
ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/

# TODO most of them are not needed. Just check local
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync \
    && install-php-extensions \
#    bcmath \
#    gd \
#    intl \
#    opcache \
#    pdo_mysql \
    zip \
#    yaml \
#    && apk update \
    && rm -rf /var/cache/apk/* /tmp/* /var/www/html


COPY . .

# Composer Dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install \
    && rm -rf /usr/local/bin/composer;

CMD [ "php", "solve.php" ]
