# Supervisorg

**/!\ WIP**

A dashboard for supervisord.

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

To run the containers :
```shell
$ cd docker/demo
$ docker-compose up -d
```
