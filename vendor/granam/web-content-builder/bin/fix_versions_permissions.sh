#!/bin/bash

HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache' | grep -v root | head -1 | cut -d\  -f1)
echo web user: "$HTTPDUSER"
directory="$1"
set -x
setfacl --default --recursive -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX "$directory"
setfacl --recursive -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX "$directory"
chgrp "$HTTPDUSER" "$directory"
find "$directory" -mindepth 1 -type d -exec chgrp --recursive "$HTTPDUSER" {} +
ls -al "$directory"
set +x
