#!/bin/bash
cd /var/www/openprinting.org/openprinting/snap || exit
exec 2>>error.log

if [ ! -f papp_list_recipient.txt ]; then
  exit
fi

if [ ! -f error.log ]; then
  exit
fi

PAPP_RECIPIENT=''
DELIM=''
while read -r RECIPIENT
do
  PAPP_RECIPIENT="$PAPP_RECIPIENT$DELIM$RECIPIENT"
  DELIM=','
done < papp_list_recipient.txt

# Only send error log if not empty
if [ -s error.log ]; then
  MESSAGE="$(<error.log)"
  echo -e "$MESSAGE" | \
  mail -r nobody@osuosl.org -s "[INFO] OpenPrinting Error Log" "$PAPP_RECIPIENT"
  rm -f error.log
fi
