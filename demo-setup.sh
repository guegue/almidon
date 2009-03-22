#!/bin/bash
#
# demo-setup.sh debe ayudar a configurar el demo de almidon
#
cd demo
chgrp -R apache cache logs files cache templates_c
chmod -R g+w cache logs files cache templates_c
