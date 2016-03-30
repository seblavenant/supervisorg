FROM debian:wheezy

ENV DEBIAN_FRONTEND noninteractive

RUN echo 'APT::Install-Recommends "0";' >> /etc/apt/apt.conf.d/99-recommends && \
    echo 'APT::Install-Suggests "0";' >> /etc/apt/apt.conf.d/99-suggests && \
    echo "alias ll='ls -al --color'" >> /root/.bashrc

RUN apt-get -y update && \
    apt-get -y install apache2 \
                       php5-common \
                       php5-dev \
                       php5-xdebug \
                       php5-cli \
                       php5-intl \
                       php-pear \
                       php5-json \
                       php5-mcrypt \
                       libapache2-mod-php5 \
                       build-essential && \
    php5enmod xdebug && \
    apt-get -y purge build-essential \
                     php5-dev && \
    apt-get -y autoremove && \
    rm -f /etc/apache2/sites-enabled/* && \
    sed -i 's/display_errors = Off/display_errors = On/g' /etc/php5/apache2/php.ini && \
    a2enmod rewrite

EXPOSE 80 443

CMD [ "/usr/sbin/apache2ctl", "-D", "FOREGROUND" ]
