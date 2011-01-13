#!/bin/bash
#
# site-setup.sh debe ayudar a configurar un sitio web para trabajar con almidon
# copia una serie de archivos que son indispensables y establece permisos.
#
source config.sh
ROOTDIR=$WWWDIR/$1

cuser="`whoami`"

#Sólo ejecuta correctamente este script sólo si el usuario quien lo manda a ejecutar
#es el usuario root, quien es el nombre del administrador del sistema.

 if [ -z $3 ]; then
   echo "Usage: ./setup domain username password"
   exit
 fi
if [ $cuser = "root" ];then
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
 cp $ALMIDONDIR/demo/classes/* $ROOTDIR/classes/
 perl -pi -e "s/local.almidon.org/$1/g" $ROOTDIR/classes/config.php
 perl -pi -e "s/almideondemo/$2/g" $ROOTDIR/classes/config.php
 echo "Creando base de datos '$2'"
 # comentado para no borrar BD de casualidad:
 #runuser -c "dropdb -SDRP $2" postgres
 echo "Entrando como superusario del Servidor Postgresql";
 echo "Recuerde escribir en la consola el comando exit para salir de la"; 
 echo "consola del Servidor Postgresql luego de importación y ejecutación";
 echo "automática de los scripts necesarios para crear el sitio.";
 su - postgres
 echo "createuser -SDR $2" 
 echo "createdb -O$2 $2" 
 psql -f $ALMIDONDIR/sql/almidon.sql
 psql -c "ALTER USER $2 WITH PASSWORD '$3'" $2; 
 exit
else
    echo "Must be the administrator named root for execute this script without any problem";
    echo "Try again with as administrator";
    exit
fi

