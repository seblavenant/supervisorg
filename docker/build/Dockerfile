FROM busybox

COPY ./src/assets /var/www/supervisorg/assets
COPY ./src/config /var/www/supervisorg/config
COPY ./src/src /var/www/supervisorg/src
COPY ./src/vendor /var/www/supervisorg/vendor
COPY ./src/views /var/www/supervisorg/views
COPY ./src/www /var/www/supervisorg/www

RUN mkdir /var/www/supervisorg/var

VOLUME /var/www/supervisorg
