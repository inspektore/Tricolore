#!/bin/sh

VERSION=`phpenv version-name`

if [ "$VERSION" -eq "hhvm" ]
then
    echo "hhvm.libxml.ext_entity_whitelist=file,http,https" >> /etc/hhvm/php.ini
fi