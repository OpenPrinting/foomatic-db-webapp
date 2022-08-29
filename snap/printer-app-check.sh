#!/bin/bash
cd /var/www/openprinting.org/openprinting/snap || exit
exec 2>>error.log

if [ ! -f papp_list_recipient.txt ]; then
  exit
fi

PAPP_RECIPIENT=''
DELIM=''
while read -r RECIPIENT
do
  PAPP_RECIPIENT="$PAPP_RECIPIENT$DELIM$RECIPIENT"
  DELIM=','
done < papp_list_recipient.txt

if [ ! -f applist.txt ]; then
  touch applist.txt
fi

snap search printer | awk '(NR>1) {print $1}' | sort -V > applist-new.txt
# Only do checks if the new applist is NOT empty
# Sometimes snap restarts itself which can overlap with this script
# Appears in this script as an empty new applist, so check for that
if [ -s applist-new.txt ]; then
  diff -u applist.txt applist-new.txt > applist.diff
  if [ -s applist.diff ]; then
    MESSAGE="The following is a diff containing the newly added and removed potential printer applications.\n$(<applist.diff)"
    echo -e "$MESSAGE" | \
    mail -r nobody@osuosl.org -s "[INFO] New Printer Applications for OpenPrinting" "$PAPP_RECIPIENT"
  fi
  rm -f applist.txt && mv -f applist-new.txt applist.txt
  rm -f applist.diff
else
  rm -f applist-new.txt
fi
