#! /bin/sh

set -e
set -u

[ $# -ne 2 ] && { echo "Usage: $0 [git reference] [tag]"; exit 1; }

GIT_REF=$1
TAG=$2

ORIGINAL_DIR=$(pwd)
# [ -d src ] && { echo "The src directory already exists." ; exit 1; }
if [ ! -d src ]; then
    git clone https://github.com/lebris/supervisorg.git src
    OLD_PWD=$(pwd)
    cd src
    git checkout $GIT_REF
    cd ${OLD_PWD}
fi

rm -f $(pwd)/src/composer.lock

mkdir -p var www/css www/js www/fonts

docker run -v $(pwd)/src:/var/www/html \
        --rm \
        -ti \
        php:7.0-apache \
        /bin/sh -c 'apt-get update && apt-get install -y git zlib1g-dev && docker-php-ext-install zip && cd /var/www/html && ls -la /var/www/html && curl -sS https://getcomposer.org/installer | php && php composer.phar install --no-dev --optimize-autoloader'

docker run -v $(pwd)/src:/var/www/html \
        --rm \
        -ti \
        php:7.0-apache \
        /bin/sh -c 'cd /var/www/html && sh ./lessc'

docker run -v $(pwd)/src:/var/www/html \
    --rm \
    -ti \
    php:7.0-apache \
    /bin/sh -c 'cd /var/www/html && vendor/bin/karma hydrate --env=prod'

docker run -v $(pwd)/src:/var/www/html \
    --rm \
    -ti \
    php:7.0-apache \
    /bin/sh -c 'cd /var/www/html && php package assetic:dump'


cd $ORIGINAL_DIR

make TAG=${TAG}

# rm -rf src
