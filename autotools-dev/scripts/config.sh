#!/bin/bash
#
# config.sh establece las variables para otros shell que ayudan a configurar almidon
#
#

# APACHE SERVERS
DEFAULT_APACHE=apache2
ALTERNATIVE_APACHE=httpd

if [ "/etc/$DEFAULT_APACHE" ];then
APACHE=$DEFAULT_APACHE
APACHEUSER="www-data"
fi 

if [ "/etc/$ALTERNATIVE_APACHE" ];then
APACHE=$ALTERNATIVE_APACHE
APACHEUSER="www"
fi 


# Web dir
DEFAULT_CONTENTDIR="/var/www"
OPTIONAL_CONTENTDIR="/srv/www"
if [ -d $DEFAULT_CONTENTDIR ];then
  CONTENTDIR=$DEFAULT_CONTENTDIR
else
    if [ -d $OPTIONAL_CONTENTDIR ];then
       CONTENTDIR=$OPTIONAL_CONTENTDIR
    fi
fi

ALMIDONDIR=$CONTENTDIR/almidon
TEMPLATE_WEBSITE_ALMIDONDIR=/usr/share/almidon

# Apache
if [ -d /etc/apache2/conf.d ]; then
  APACHE=apache2
  APACHEUSER=www-data
fi
if [ -d /etc/httpd/conf.d ]; then
  APACHE=httpd
  APACHEUSER=www
fi
