#!/bin/bash

if [ ! -f ./theme_setup_done ]; then
  rm -rf ./themes/wds_bt composer.lock package-lock.json node_modules vendor
  ./setup/install_jq.sh && ./setup/setup_theme.sh
else
  echo "Setup already completed. Delete .theme_setup_done to run again."
fi
