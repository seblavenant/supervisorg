#! /bin/sh

set -e
set -u

[ $# -ne 1 ] && { echo "Usage: $0 git reference (hash, tag, branch name)"; exit 1; }

GIT_REF=$1

ORIGINAL_DIR=$(pwd)
[ -d src ] && { echo "The src directory already exists." ; exit 1; }
git clone https://github.com/lebris/supervisorg.git src
cd src
git checkout $GIT_REF

mkdir -p var www/css www/js www/fonts
php composer.phar install --no-dev --optimize-autoloader
sh ./lessc
vendor/bin/karma hydrate --env=prod
php console assetic:dump

cd $ORIGINAL_DIR

make

rm -rf src
