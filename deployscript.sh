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

printf "\nCopying files over to server\n\n"

printf "\n\t root --> Server\n\n"
scp * ${SCP_DIR}/

# General static files
# Basically copy everything at root level
printf "\n\tassets/* --> Server/assets/* \n\n"
scp assets/* ${SCP_DIR}/assets/

# JavaScript
printf "\n\tassets/dist/ --> Server/assets/ \n\n"

scp -r assets/dist/ ${SCP_DIR}/assets/

# Model
printf "\n\tModel/ --> Server/ \n\n"
scp -r Model/ ${SCP_DIR}/

# Repository
printf "\n\tRepository/ --> Server/ \n\n"
scp -r Repository/ ${SCP_DIR}/

# response
printf "\n\tresponse/ --> Server/ \n\n"
scp -r response/ ${SCP_DIR}/

# Service
printf "\n\tService/ --> Server/ \n\n"
scp -r Service/ ${SCP_DIR}/

# Setup
printf "\n\tSetup/ --> Server/ \n\n"
scp -r Setup/ ${SCP_DIR}/

# Setup
printf "\n\tview/ --> Server/ \n\n"
scp -r view/ ${SCP_DIR}/

# Widget
printf "\n\tWidget/ --> Server/ \n\n"
scp -r Widget/ ${SCP_DIR}/

printf "\n\nRemoving .git in remote\n\n"

ssh ${SSH} "rm -rf ${PLUGIN_DIR}/.git/"

printf "\n\nDeployment complete!\n"
