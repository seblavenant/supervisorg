# Usage

Avoid directories owned by `www-data` on your host machine by mapping your user and group id to `www-data` in the container.

```
$ USER_ID=$(id -u) GROUP_ID=$(id -g) docker-compose up -d
```
