#!/bin/bash
#
# setup.sh debe ayudar a configurar un sitio web para trabajar con almidon
# copia una serie de archivos que son indispensables, otros que son utiles
# pero su uso es opcional, igual se puede copiar el demo y usar como punto
# de partida
#
if [ -z $2 ]; then
  echo "Usage: ./setup username domain pass"
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
cp /www/cms/new/.htaccess .
perl -pi -e "s/example.org/$2/g" .htaccess
perl -pi -e "s/example/$1/g" .htaccess
ln -s ../classes
cd ..
cp /www/cms/new/* classes/
perl -pi -e "s/example.org/$2/g" classes/config.php
perl -pi -e "s/example/$1/g" classes/config.php
echo createuser -Upostgres -SDRP $1
echo createdb -Upostgres -O$1 $1
