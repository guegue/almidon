#!/bin/bash
#
# demo-setup.sh debe ayudar a configurar el demo de almidon
#
source config.sh
cd demo

echo "Instalando demo de almidon en `pwd` `date`" > logs/install.log

# config apache
echo "Configurando '$APACHE'" >> logs/install.log
cp almidon.conf /etc/$APACHE/conf.d/
/etc/init.d/$APACHE reload

# crea base de datos
# echo "Creando base de datos segun demo.sql"
runuser -c "psql -f demo.sql" postgres >> logs/install.log 2>&1

echo "Creando dirs y permisos para '$APACHEUSER'" >> logs/install.log
# permisos de dirs escribibles
chgrp -R $APACHEUSER cache logs files cache templates_c
chmod -R g+w cache logs files cache templates_c

# config y tables configurables desde el web
chgrp $APACHEUSER classes/config.php classes/tables.class.php
chmod g+w classes/config.php classes/tables.class.php

echo "Log de instalacion: demo/logs/install.log"
echo "!! No olvides configurar pg_hba.conf !!"
