FROM supervisorg/demo/php

RUN apt-get -y update && \
    apt-get -y install apache2 \
                       libapache2-mod-php5 && \
    apt-get -y autoremove && \
    rm -f /etc/apache2/sites-enabled/* && \
    sed -i 's/display_errors = Off/display_errors = On/g' /etc/php5/apache2/php.ini && \
    a2enmod rewrite 

RUN ln -s /etc/php5/mods-available/amqp.ini /etc/php5/apache2/conf.d/20-amqp.ini && \
    rm -rf /var/lib/apt/lists/* && \
    rm -f /etc/apache2/sites-enabled/* && \
    rm -rf /var/lock/apache2/

RUN ls -la /var/lock/

EXPOSE 80 443

ADD bootstrap.sh /root/bootstrap.sh

CMD [ "sh", "/root/bootstrap.sh" ]
