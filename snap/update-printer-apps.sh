#!/bin/bash
cd /var/www/openprinting.org/openprinting/snap || exit
exec 2>>error.log

if [ ! -f printer-apps.txt ]; then
  exit
fi

while read -r package_line
do
  package=""
  if [ "$(echo "$package_line" | awk '{ print NF }')" -gt 1 ]
  then
    package="$(echo "$package_line" | awk '{ print $1 }')"
  else
    package="$package_line"
  fi

  installed=$(snap list | grep -c "$package")
  if [ "$installed" -eq 0 ]
  then
    snap install --edge "$package"
    snap stop "$package"
    rm -f cache/*.out  # Clear the cache on new papp
  else
    enabled=$(snap services | grep -E -c "$package.*\s+enabled")
    if [ "$enabled" -eq 0 ]
    then
      snap enable "$package"
    fi

    stopped=$(snap services | grep -E -c "$package.*\s+inactive")
    if [ "$stopped" -eq 0 ]
    then
      snap stop "$package"
    fi
  fi
done < printer-apps.txt
