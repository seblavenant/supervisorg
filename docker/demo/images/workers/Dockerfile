FROM supervisorg/demo/php

RUN apt-get -y update && \
    apt-get -y install supervisor \
                       psmisc \
                       procps && \
    apt-get -y autoremove && \
    rm -rf /var/lib/apt/lists/* && \
    sed 's%^files = .*%files = /etc/supervisor/system.conf.d/*.conf /etc/supervisor/conf.d/*.conf%g' -i /etc/supervisor/supervisord.conf

ADD system.conf.d /etc/supervisor/system.conf.d
ADD bootstrap.sh /root/bootstrap.sh

CMD [ "sh", "/root/bootstrap.sh" ]
