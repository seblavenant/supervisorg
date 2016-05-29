# Supervisorg

**/!\ WIP**

A dashboard for supervisord.


**Warning** : the readme might not be up to date. To test supervisorg build all docker images in the `docker/demo/images` directory then run the following command

```
USER_ID=$(id -u) GROUP_ID=$(id -g) WEB_PORT=80 RMQ_PORT=15672 docker-compose up -d
```


## Installation

```shell
$ git clone https://github.com/lebris/supervisorg.git supervisorg
$ cd supervisord
$ php composer.phar install
$ ./lessc
$ vendor/bin/karma hydrate
$ php console assetic:dump
```

## Try it !
The demo requires 2 images that you need to build before.

```shell
$ cd docker/demo/images/php-apache
$ docker build --rm=true -t supervisorg/demo/php-apache .
```

```shell
$ cd docker/demo/images/supervisord
$ docker build --rm=true -t supervisorg/demo/supervisord .
```

**Note** : you can also use the `make` in the image directories to build them.

Then run the containers :
```shell
$ cd docker/demo
$ docker-compose up -d
```

To access to the dashboard `http://<DOCKER_MACHINE_IP>`
