#!/bin/bash
#
# config.sh establece las variables para otros shell que ayudan a configurar almidon
#
#

ALMIDONDIR=/var/www/almidon
WWWDIR=/var/www
APACHE=httpd
APACHEUSER=apache


# Web dir
if [ -d /srv/www ]; then
  WWWDIR=/srv/www
fi
if [ -d /var/www ]; then
  WWWDIR=/var/www
fi

# Almidon dir

# Almidon dir
if [ -d /usr/local/almidon ]; then
  ALMIDONDIR=/usr/local/almidon
fi
if [ -d /var/www/almidon ]; then
  ALMIDONDIR=/var/www/almidon
fi

# Apache
if [ -d /etc/apache2/conf.d ]; then
  APACHE=apache2
  APACHEUSER=www
fi
if [ -d /etc/httpd/conf.d ]; then
  APACHE=httpd
  APACHEUSER=apache
fi
