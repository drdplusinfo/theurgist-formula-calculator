#!/bin/bash

HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache' | grep -v root | head -1 | cut -d\  -f1)
echo web user: "$HTTPDUSER"
set -x
    setfacl --default --recursive -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX cache
    setfacl --recursive -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX cache
    chgrp "$HTTPDUSER" cache
    find cache -mindepth 1 -type d -exec chgrp --recursive "$HTTPDUSER" {} +
    ls -al cache
set +x
