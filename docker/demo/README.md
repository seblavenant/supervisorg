# Usage

Avoid directories owned by `www-data` on your host machine by mapping your user and group id to `www-data` in the container.

```
$ cd images/php && make && cd ../..
$ USER_ID=$(id -u) GROUP_ID=$(id -g) WEB_PORT=80 RMQ_PORT=15672 docker-compose up -d
```
