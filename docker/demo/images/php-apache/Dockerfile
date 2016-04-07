FROM debian:jessie

ENV DEBIAN_FRONTEND noninteractive

RUN echo 'APT::Install-Recommends "0";' >> /etc/apt/apt.conf.d/99-recommends && \
    echo 'APT::Install-Suggests "0";' >> /etc/apt/apt.conf.d/99-suggests && \
    echo "alias ll='ls -al --color'" >> /root/.bashrc

RUN apt-get -y update && \
    apt-get -y install apache2 \
                       php5-common \
                       php5-xdebug \
                       php5-cli \
                       php5-intl \
                       php5-json \
                       php5-mcrypt \
                       libapache2-mod-php5 && \
    apt-get -y autoremove && \
    rm -f /etc/apache2/sites-enabled/* && \
    sed -i 's/display_errors = Off/display_errors = On/g' /etc/php5/apache2/php.ini && \
    a2enmod rewrite 

RUN apt-get -y install wget \
                       tar \
                       make \
                       php5-dev \
                       pkg-config \
                       git \
                       php-pear

RUN wget --no-check-certificate https://github.com/alanxz/rabbitmq-c/releases/download/v0.7.1/rabbitmq-c-0.7.1.tar.gz && \
    tar xf rabbitmq-c-0.7.1.tar.gz && \
    cd rabbitmq-c-0.7.1 && \
    autoreconf -i && ./configure && make && make install && \
    pecl install amqp
    
RUN echo 'extension=amqp.so' >> /etc/php5/mods-available/amqp.ini && \
    ln -s /etc/php5/mods-available/amqp.ini /etc/php5/apache2/conf.d/20-amqp.ini && \
    ln -s /etc/php5/mods-available/amqp.ini /etc/php5/cli/conf.d/20-amqp.ini

RUN rm -rf /var/lib/apt/lists/* && \
    rm -f /etc/apache2/sites-enabled/* && \
    rm -rf /var/lock/apache2/

RUN ls -la /var/lock/

EXPOSE 80 443

ADD bootstrap.sh /root/bootstrap.sh

CMD [ "sh", "/root/bootstrap.sh" ]
