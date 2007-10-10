#!/bin/sh
#echo `date`
mysqlshow --user=root --password=pass|grep -v Databases|grep -v "-"|cut -d" " -f2|xargs -i echo "mysqldump --user=root --password=pass --opt {} >/backup/data/{}.mysql"|sh
#echo `date`
