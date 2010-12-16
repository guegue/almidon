#!/bin/bash
#
# site-setup.sh debe ayudar a configurar un sitio web para trabajar con almidon
# copia una serie de archivos que son indispensables y establece permisos.
#

source config.sh

if [ -z $1 ]; then
  echo "Domain invalid, the first argument should not be an empty string."
  exit
fi

if [ -z $2 ]; then
  echo "Administrator Username invalid, the second argument should not be an empty string."
  exit
fi

if [ -z $3 ]; then
  echo "Password invalid, the third argument should not be an empty string."
  exit
fi

if [ -z $4 ]; then
  echo "Public Username invalid, the fourth argument should not be an empty string."
  exit
fi

if [ -z $5 ]; then
  echo "Password invalid, the fifth argument should not be an empty string."
  exit
fi

if [ -z $6 ]; then
  echo "databaseServername invalid, the sixth argument should not be an empty string."
  echo " Usage: ./setup domain adminUsername adminUserpassword publicUsername publicUserpassword  databaseServername"
  exit
  else
       
       if [ "$6" == "mysql" ]; then     
         if [ ! -d "/etc/mysql" ]; then
           echo "Please install package mysql, try again."
           exit
          fi 
       else
          if [ "$6" == "postgresql" ]; then
           if [ ! -d "/etc/postgresql" ]; then
             echo "Please install package posgresql, try again."
             exit
           fi
          fi                                                                     
       fi       
        
fi

echo "";

if [ ! -d $ALMIDONDIR ]; then
 mkdir $ALMIDONDIR;
fi

ROOTDIR=$ALMIDONDIR/$1

if [ -d  $ROOTDIR ]; then
  #ROOTDIR_total_size="`du -s $ROOTDIR`" 
  #if [ $ROOTDIR_total_size -gt 0 ];then
   echo " Domain already exists, please write other domain different."
  #fi
 exit
 else
       mkdir $ROOTDIR
fi

#Configurar Almidon

echo "Creando dirs en '$ROOTDIR'"

cp -Rf $TEMPLATE_WEBSITE_ALMIDONDIR/core                              $ROOTDIR/core

TEMPLATE_WEBISITE_ALMIDON_DEMODIR=$TEMPLATE_WEBSITE_ALMIDONDIR/demo

cp -Rf  $TEMPLATE_WEBISITE_ALMIDON_DEMODIR     $ROOTDIR/demo
#cp -Rf $TEMPLATE_WEBISITE_ALMIDON_DEMODIR/cache                       $ROOTDIR/cache
#cp -Rf $TEMPLATE_WEBISITE_ALMIDON_DEMODIR/classes                     $ROOTDIR/classes
#cp -Rf $TEMPLATE_WEBISITE_ALMIDON_DEMODIR/files                       $ROOTDIR/files
#cp -Rf $TEMPLATE_WEBISITE_ALMIDON_DEMODIR/logs                        $ROOTDIR/logs
#cp -Rf $TEMPLATE_WEBISITE_ALMIDON_DEMODIR/misc                        $ROOTDIR/misc
#cp -Rf $TEMPLATE_WEBISITE_ALMIDON_DEMODIR/public_html                 $ROOTDIR/public_html
#cp -Rf $TEMPLATE_WEBISITE_ALMIDON_DEMODIR/secure                      $ROOTDIR/secure
#cp -Rf $TEMPLATE_WEBISITE_ALMIDON_DEMODIR/sql                         $ROOTDIR/sql
#cp -Rf $TEMPLATE_WEBISITE_ALMIDON_DEMODIR/templates                   $ROOTDIR/templates
#cp -Rf $TEMPLATE_WEBISITE_ALMIDON_DEMODIR/templates_c                 $ROOTDIR/templates_c

ln -s $ROOTDIR/demo/files $ROOTDIR/demo/public_html

cp -Rf $TEMPLATE_WEBSITE_ALMIDONDIR/doc $ROOTDIR/doc;

echo "Instalando Sitio Web creado bajo la plataforma de Almidón en '$ROOTDIR' `date`" > $ROOTDIR/demo/logs/install.log

echo "Configurando '$ROOTDIR/demo/classes/config.php'"
perl -pi -e "s/local.almidon.org/$1/g" $ROOTDIR/demo/classes/config.php

perl -pi -e "s/almidondemo/$2/g" $ROOTDIR/demo/classes/config.php
perl -pi -e "s/secreto1/$3/g" $ROOTDIR/demo/classes/config.php

perl -pi -e "s/almidondemowww/$4/g" $ROOTDIR/demo/classes/config.php
perl -pi -e "s/secreto2/$5/g" $ROOTDIR/demo/classes/config.php

echo "Configurando 'scripts SQL'"
perl -pi -e "s/almidondemo/$2/g" $ROOTDIR/demo/create.sql
perl -pi -e "s/secreto1/$3/g" $ROOTDIR/demo/create.sql 
  
perl -pi -e "s/almidondemowww/$4/g" $ROOTDIR/demo/create.sql
perl -pi -e "s/secreto2/$5/g" $ROOTDIR/demo/create.sql  

perl -pi -e "s/almidondemo/$2/g" $ROOTDIR/demo/demo.mysql
perl -pi -e "s/secreto1/$3/g" $ROOTDIR/demo/sql/demo.mysql 
  
perl -pi -e "s/almidondemowww/$4/g" $ROOTDIR/demo/demo.mysql
perl -pi -e "s/secreto2/$5/g" $ROOTDIR/demo/demo.mysql
 
# config apache
echo "Log de instalacion: $ROOTDIR/demo/logs/install.log"
echo "Configurando '$APACHE'" >> $ROOTDIR/demo//logs/install.log

mv $ROOTDIR/demo/apache2/conf.d/demo.almidon.conf    $ROOTDIR/demo/apache2/conf.d/$1.conf
perl -pi -e "s/local.almidon.org/$1/g"  $ROOTDIR/demo/apache2/conf.d/$1.conf

cp $ROOTDIR/demo/apache2/conf.d/$1.conf   /etc/apache2/conf.d  

# mkdir $ROOTDIR/cron.d
# cp $TEMPLATE_WEBISITE_ALMIDON_DEMODIR/cron.d/almidon $ROOTDIR/cron.d/$1
# cp $ROOTDIR/cron.d/$1 /etc/cron.d  

#Activar el modrewrite en Apache2
a2enmod rewrite

#Forzar la recarga de Apache2
/etc/init.d/$APACHE force-reload

#Reinicia el servidor Apache2
/etc/init.d/$APACHE restart


# permisos de dirs escribibles, leídos sólo para el propietario y al grupo que pertenece éste.
#Permisos del demo: 'templates_c', 'logs', 'cache' debe ser escribible por el usuario web.
#Igual 'files' y sus subcarpertas.
chmod -R 755 $ROOTDIR/core $ROOTDIR/demo/cache $ROOTDIR/demo/logs $ROOTDIR/demo/files $ROOTDIR/demo/templates $ROOTDIR/demo/templates_c $ROOTDIR/demo/sql

chmod -R 755 $ROOTDIR/demo/classes/config.php $ROOTDIR/demo/classes/tables.class.php 

# config y tables configurables desde el web
echo "Estableciendo permisos para '$APACHEUSER'"
#useradd "-u $2 -g $APACHEGROUP"
#chown -R $2:$APACHEUSER $ROOTDIR


echo "Creando dirs y permisos para '$APACHEUSER'" >> $ROOTDIR/demo/logs/install.log
# permisos de dirs escribibles
chgrp -R $APACHEUSER $ROOTDIR/demo/cache $ROOTDIR/demo/classes $ROOTDIR/demo/logs $ROOTDIR/demo/files $ROOTDIR/demo/templates_c

echo "Creando base de datos '$2'";
# comentado para no borrar BD de casualidad:
echo "";

echo "";
if [  "$6" = "mysql" ]; then
 echo "Instalando sql para Mysql" >> "$ROOTDIR/demo/logs/install.log"
  mysql < "$ROOTDIR/demo/sql/demo.mysql" >> "$ROOTDIR/demo/logs/install.log" 2>&1
  perl -pi -e 's/pgsql/mysql/g' $ROOTDIR/demo/classes/config.php > $ROOTDIR/demo/classes/config.orig.php
fi

if [ "$6" = "postgresql" ]; then
  echo "Instalando sql para Postgresql" >> "$ROOTDIR/demo/logs/install.log"

  #Opción 1
  echo "Ingreso al servidor Postgresql sin necesidad de auntenticarse.";
  echo "Ingresando al Servidor Postgresql como usuario postgres, recuerde escribir";
  echo "en la terminal el comando exit luego de la importación y ejecución de los";
  echo "scripts .sql para volver a ser el usuario root.";
  su - postgres
  echo "Importando y ejecutando scripts .sql"
  psql -f $ROOTDIR/demo/sql/create.sql
  psql -f $ROOTDIR/core/sql/alm.tables.sql
  psql -f $ROOTDIR/demo/sql/demo.sql 
  psql -f $ROOTDIR/demo/sql/country.sql 
  

  #Opción 2
  # Requiere que se cambié la contraseña por defecto del usuario(administrador) postgres.
  # Pasos para cambiar la contraseña  por defecto del usuario(administrador) postgres como root:
  # 1. Cambiando a usuario postgres: su - postgres
  # 2. Ingresando a la consola interactiva de 'postgres': psql
  # 3. Cambiando contraseña del usuario(administrador)'postgres': ALTER USER postgres WITH  
  #    PASSWORD 'nueva_contraseña';
  # 4. Saliendo de la consola interactiva de 'postgres':\q
  # 5. Volviendo a ser usuario 'root': exit
  # 5. Accediendo a la consola interactiva de postgres con la nueva contraseña de usuario
  #   (administrador) postgres: psql -U postgres -W
  #   A continuación se le pedirá la nueva contraseña, introduzcala.
  # 6. Si logra ingresar exitosamente y desea salir escriba: \q

  #echo "Ingresando al Servidor Postgres, recuerde introducir la contraseña del"
  #echo "administrador del servidor para auntenticarse.";

  #psql -U postgres -W 
  # \i $ROOTDIR/sql/demo.sql 
  # \i $ROOTDIR/sql/country.sql 
  # \q #Sale de la consola interactiva del Servidor de Base de Datos Postgresql.

  echo "Configurando pg_hba.conf"
  #pghba=/var/lib/pgsql/data/pg_hba.conf
  
  #Debian 
  pghba=/etc/postgresql/8.3/main/pg_hba.conf
  grep "almidondemo" $pghba > /dev/null
  if [ "$?" == "1" ]; then
   echo "local almidondemo all md5">>$pghba
   /etc/init.d/postgresql-8.3 restart
  fi
fi

chown -R $APACHEUSER:$APACHEUSER $ROOTDIR
echo "Creación y configuración exitosa del Sitio Web."




