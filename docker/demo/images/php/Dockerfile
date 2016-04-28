FROM debian:jessie

ENV DEBIAN_FRONTEND noninteractive

RUN echo 'APT::Install-Recommends "0";' >> /etc/apt/apt.conf.d/99-recommends && \
    echo 'APT::Install-Suggests "0";' >> /etc/apt/apt.conf.d/99-suggests && \
    echo "alias ll='ls -al --color'" >> /root/.bashrc

RUN apt-get -y update && \
    apt-get -y install php5-common \
                       php5-xdebug \
                       php5-cli \
                       php5-intl \
                       php5-json \
                       php5-mcrypt \
                       wget \
                       tar \
                       make \
                       php5-dev \
                       pkg-config \
                       git \
                       php-pear && \
    apt-get -y autoremove

RUN wget --no-check-certificate https://github.com/alanxz/rabbitmq-c/releases/download/v0.7.1/rabbitmq-c-0.7.1.tar.gz && \
    tar xf rabbitmq-c-0.7.1.tar.gz && \
    cd rabbitmq-c-0.7.1 && \
    autoreconf -i && ./configure && make && make install && \
    pecl install amqp && \
    echo 'extension=amqp.so' >> /etc/php5/mods-available/amqp.ini && \
    ln -s /etc/php5/mods-available/amqp.ini /etc/php5/cli/conf.d/20-amqp.ini

RUN pecl install mongodb && \
    echo 'extension=mongodb.so' >> /etc/php5/mods-available/mongodb.ini && \
    ln -s /etc/php5/mods-available/mongodb.ini /etc/php5/cli/conf.d/20-mongodb.ini
    
RUN rm -rf /var/lib/apt/lists/*
