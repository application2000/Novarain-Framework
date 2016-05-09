#!/bin/bash
#
# CD to a Joomla root directory first

SOURCE=/var/webdisk/novarain/extensions/nrframework/source
DESTINATION="${PWD}"

# ln -s $SOURCE/media/* $DESTINATION/media/
ln -s $SOURCE/plugins/system/* $DESTINATION/plugins/system/
 

