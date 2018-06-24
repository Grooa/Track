#!/bin/bash

SSH=""
PLUGIN_DIR="/www/Plugin/Track"

printf "Setting variables\n"

if [[ -z "${GROOA_SSH}" ]]; then
    echo "DEPLOYMENT ERROR: Missing env variable GROOA_SSH, for ssh address to production server"
    exit 1
else
    SSH="${GROOA_SSH}"
fi

SCP_DIR="${SSH}:${PLUGIN_DIR}"

##
# 1. Prepare to deploy
##

printf "\nBundling scripts\n\n"

# Prepare js
printf "Bundling JavaScript\n"
npm run deploy

##
# 2. Deploy
##

printf "Moving files\n"
rsync -r --exclude 'node_modules' --exclude 'git' . ${SCP_DIR}
