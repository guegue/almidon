#!/bin/bash
#
# demo-setup.sh configura el demo de almidon
#
source config.sh

cuser="`whoami`"

#Sólo ejecuta correctamente este script sólo si el usuario quien lo manda a ejecutar
#es el usuario root, quien es el nombre del administrador del sistema.
if [ $cuser = "root" ];then
 cd ../demo

 echo "Instalando demo de almidon en `pwd` `date`" > logs/install.log

 # config apache
 echo "Configurando '$APACHE'" >> logs/install.log
 cp demo.almidon.conf /etc/$APACHE/conf.d/
 /etc/init.d/$APACHE restart

 cp classes/config.ori.php classes/config.php

 # crea base de datos
 # echo "Creando base de datos segun demo.sql"
 if [ "$1" == "mysql" ]; then
  echo "Instalando sql para Mysql" >> logs/install.log
  mysql < demo.mysql >> logs/install.log 2>&1
  perl -pi -e 's/pgsql/mysql/' classes/config.php > classes/config.php
 else
   echo "Instalando sql para Postgresql" >> logs/install.log
   echo "Entrando como superusuario del Servidor Postgresql";
   echo "Recuerde escribir en la consola el comando exit para salir de la"; 
   echo "consola interactiva del Servidor Postgresql luego de importación y ejecutación";
   echo "automática de los scripts necesarios para crear el sitio."
   su - postgres
   "psql -f demo.sql" >> logs/install.log 2>&1
   "psql -f country.sql" >> logs/install.log 2>&1
   exit
 fi
 grep "local.almidon.org" /etc/hosts > /dev/null
 if [ "$?" == "1" ]; then
  echo "127.0.0.1 local.almidon.org">>/etc/hosts
 fi
 pghba=/var/lib/pgsql/data/pg_hba.conf
 grep "almidondemo" $pghba > /dev/null
 if [ "$?" == "1" ]; then
  echo "local almidondemo all md5">>$pghba
  /etc/init.d/postgresql restart
 fi

 echo "Creando dirs y permisos para '$APACHEUSER'" >> logs/install.log
 # permisos de dirs escribibles
 chgrp -R $APACHEUSER cache logs files cache templates_c
 chmod -R g+w cache logs files cache templates_c

 # config y tables configurables desde el web
 chgrp $APACHEUSER classes/config.php classes/tables.class.php
 chmod g+w classes/config.php classes/tables.class.php

 echo "Log de instalacion: demo/logs/install.log"
else
    echo  "Must be the administrator named root for execute this script without any problem";
    echo "Try again with as administrator";
fi
#End of script sh
