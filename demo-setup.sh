#!/bin/bash
#
# demo-setup.sh debe ayudar a configurar el demo de almidon
#
source config.sh
cd demo
chgrp -R $APACHEUSER cache logs files cache templates_c
chmod -R g+w cache logs files cache templates_c
