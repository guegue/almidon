#!/bin/bash
#
# demo-setup.sh configura el demo de almidon
#

DEFAULT_DOMAIN="local.almidon.org"
DEFAULT_ADMINUSERNAME="almidondemo"
DEFAULT_ADMINUSERPASSWORD="secreto1" 
DEFAULT_PUBLICUSERNAME="almidondemowww" 
DEFAULT_PUBLICUSERPASSWORD="secreto2" 
DEFAULT_DATABASESERVERNAME="postgresql"

if [ ! -x site-setup.sh ];then 
 echo "`chmod +x site-setup.sh `"
fi

./site-setup.sh $DEFAULT_DOMAIN $DEFAULT_ADMINUSERNAME $DEFAULT_ADMINUSERPASSWORD $DEFAULT_PUBLICUSERNAME $DEFAULT_PUBLICUSERPASSWORD $DEFAULT_DATABASESERVERNAME


