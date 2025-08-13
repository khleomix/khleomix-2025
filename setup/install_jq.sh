#!/bin/bash

# Install jq based on the operating system.
if [[ "$OSTYPE" == "linux-gnu"* ]]; then
	# Linux.
	if [ -x "$(command -v apt-get)" ]; then
		sudo apt-get update
		sudo apt-get install -y jq
	elif [ -x "$(command -v yum)" ]; then
		sudo yum install -y epel-release
		sudo yum install -y jq
	else
		echo "Error: Package manager not supported."
		exit 1
	fi
elif [[ "$OSTYPE" == "darwin"* ]]; then
	# macOS.
	if ! [ -x "$(command -v brew)" ]; then
		echo "Error: Homebrew is not installed. Please install Homebrew and try again."
		exit 1
	fi
	brew install jq --quiet
else
	echo "Error: Operating system not supported."
	exit 1
fi

echo "jq installation complete."

