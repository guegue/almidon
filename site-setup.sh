#!/bin/bash
#
# setup.sh debe ayudar a configurar un sitio web para trabajar con almidon
# copia una serie de archivos que son indispensables y establece permisos.
#
if [ -z $3 ]; then
  echo "Usage: ./setup domain username password"
  exit
fi
source config.sh
ROOTDIR=$WWWDIR/$1
echo "Creando dirs en '$ROOTDIR'"
if [ ! -d $ROOTDIR/public_html ]; then
  mkdir -p $ROOTDIR/public_html
fi
if [ ! -d $ROOTDIR/classes ]; then
  mkdir $ROOTDIR/classes $ROOTDIR/files $ROOTDIR/logs $ROOTDIR/secure $ROOTDIR/templates $ROOTDIR/templates_c $ROOTDIR/cache
fi
echo "Estableciendo permisos para '$APACHEUSER'"
chown -R $2:$APACHEUSER $ROOTDIR
chmod -R g+w $ROOTDIR/cache $ROOTDIR/logs $ROOTDIR/files $ROOTDIR/cache $ROOTDIR/templates_c $ROOTDIR/classes/config.php $ROOTDIR/classes/tables.class.php
ln -s $ROOTDIR/files $ROOTDIR/public_html/
echo "Configurando '$ROOTDIR/classes/config.php'"
cp $ALMIDONDIR/new/classes/* $ROOTDIR/classes/
perl -pi -e "s/local.almidon.org/$1/g" $ROOTDIR/classes/config.php
perl -pi -e "s/almideondemo/$2/g" $ROOTDIR/classes/config.php
echo "Creando base de datos '$2'"
# comentado para no borrar BD de casualidad:
#runuser -c "dropdb -SDRP $2" postgres
runuser -c "createuser -SDR $2" postgres
runuser -c "createdb -O$2 $2" postgres
runuser -c "psql -f $ALMIDONDIR/sql/almidon.sql" postgres
runuser -c "echo \"ALTER USER $2 WITH PASSWORD '$3'\"|psql" postgres
