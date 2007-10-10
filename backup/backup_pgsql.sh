#!/bin/sh
#echo `date`
psql -Upostgres -tlA |grep -v template0|cut -d\| -f1|xargs -i pg_dump -Upostgres -f '/backup/data/{}'.dump.sql '{}'
psql -Upostgres -tlA |grep -v template0|cut -d\| -f1|xargs -i pg_dump -Upostgres -s -f '/backup/data/{}'.schema.sql '{}'
#echo `date`
