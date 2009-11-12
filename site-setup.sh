#!/bin/bash
#
# setup.sh debe ayudar a configurar un sitio web para trabajar con almidon
# copia una serie de archivos que son indispensables, otros que son utiles
# pero su uso es opcional, igual se puede copiar el demo y usar como punto
# de partida
#
if [ -z $2 ]; then
  echo "Usage: ./setup domain username"
  exit
fi
source config.sh
ROOTDIR=$WWWDIR/$1
mkdir $ROOTDIR/classes
mkdir $ROOTDIR/files
mkdir $ROOTDIR/logs
mkdir $ROOTDIR/secure
mkdir $ROOTDIR/templates
mkdir $ROOTDIR/templates_c
mkdir $ROOTDIR/cache
chown -R $2:$APACHEUSER $ROOTDIR
chmod -R g+w $ROOTDIR/cache $ROOTDIR/logs $ROOTDIR/files $ROOTDIR/cache $ROOTDIR/templates_c
ln -s $ROOTDIR/files $ROOTDIR/public_html/
cp $ALMIDONDIR/new/classes/* $ROOTDIR/classes/
perl -pi -e "s/example.org/$1/g" $ROOTDIR/classes/config.php
perl -pi -e "s/example/$2/g" $ROOTDIR/classes/config.php
echo Postgresql commands:
echo createuser -Upostgres -SDRP $2
echo createdb -Upostgres -O$2 $2
echo psql -Upostgres -f $ROOTDIR/secure/tables.sql $2
