# Build a supervisorg docker image
## Build the image

```shell
$ ./build.sh <git ref>
```

Parameters :
 * `<git_ref>` : a git commit hash, tag or branch name

This will clone the sources, prepare the application (dependencies, assets, etc.), and copy (to `/var/www/supervisorg`) supervisorg sources in the image.

## Test the built image

```shell
$ docker-compose up -d
```
