#!/bin/bash

cuser="`whoami`"

#Sólo ejecuta correctamente este script sólo si el usuario quien lo manda a ejecutar
#es el usuario root, quien es el nombre del administrador del sistema.
if [ $cuser = "root" ];then
 cd ../core/
 phpdoc -t ../doc/phpdoc/ -f php/db2.class.php,php/db.dal.php,php/db.const.php,../demo/classes/config.ori.php,php/almidon.php
else
    echo  "Must be the administrator named root for execute this script without any problem";
    echo "Try again with as administrator";
fi
#End of script sh
