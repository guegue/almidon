#!/bin/bash
if [ -z $2 ]; then
  echo "Usage: ./setup username domain"
  exit
fi
cd /www/$2
mkdir classes
mkdir files
mkdir files/galeria
mkdir files/doc
mkdir files/pagina
mkdir logs
mkdir secure
mkdir templates
mkdir templates_c
mkdir cache
chown -R $1:www *
chgrp -R apache cache logs files cache templates_c
chmod -R g+w cache logs files cache templates_c
cd public_html
ln -s ../files
cd ..
cd secure
ln -s /www/cms/php/object.php galeria.php
ln -s /www/cms/php/object.php doc.php
ln -s /www/cms/php/object.php pagina.php
ln -s /www/cms/php/object.php categoria.php
ln -s /www/cms/php/object.php enlace.php
ln -s /www/cms/php/index.php
ln -s ../classes
cd ../public_html
ln -s ../secure admin
cd ..
cp /www/cms/new/* classes/ 
cp /www/cms/new/.htaccess .
