FROM php:7.0-apache

COPY ./src/assets /var/www/supervisorg/assets
COPY ./src/config /var/www/supervisorg/config
COPY ./src/env /var/www/supervisorg/env
COPY ./src/rabbitmq /var/www/supervisorg/rabbitmq
COPY ./src/src /var/www/supervisorg/src
COPY ./src/vendor /var/www/supervisorg/vendor
COPY ./src/views /var/www/supervisorg/views
COPY ./src/www /var/www/supervisorg/www
COPY ./src/.karma /var/www/supervisorg/.karma
COPY ./src/worker /var/www/supervisorg/worker

RUN mkdir /var/www/supervisorg/var
RUN rm -r /var/www/html && \
    ln -s /var/www/supervisorg/www /var/www/html

RUN chown -Rf www-data:www-data /var/www/supervisorg

RUN a2enmod rewrite

ENV RABBITMQ_VERSION 0.7.1

RUN apt-get update && apt-get install -y automake build-essential libtool

RUN cd /tmp && \
    curl --stderr - -L -O https://github.com/alanxz/rabbitmq-c/releases/download/v0.7.1/rabbitmq-c-${RABBITMQ_VERSION}.tar.gz && \
    tar xf rabbitmq-c-${RABBITMQ_VERSION}.tar.gz && \
    cd rabbitmq-c-${RABBITMQ_VERSION} && \
    autoreconf -i && \
    ./configure && \
    make && \
    make install && \
    cd /tmp && \
    rm -rf rabbitmq-c-${RABBITMQ_VERSION} && \
    rm rabbitmq-c-${RABBITMQ_VERSION}.tar.gz

RUN pecl install amqp-1.7.0 && \
    docker-php-ext-enable amqp

#RUN pecl install amqp-1.7.0 && \
#    echo "extension=amqp.so" > /etc/php/mods-available/amqp.ini && \
#    phpenmod -v 7.0 -s ALL amqp
