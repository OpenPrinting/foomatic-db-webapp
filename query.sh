#!/bin/bash
cd /var/www/openprinting.org/openprinting/snap || exit
exec 2>>error.log
SNAP_DIR=/var/lib/snapd/snap/bin/

if [ ! -f printer-apps.txt ]; then
  exit
fi

ARGS=()

for ARG in "$@"; do
  KEY=${ARG%%=*}
  VALUE=${ARG#*=}
  ARGS+=(-o "$KEY=\"$VALUE\"")
done

while read -r PAPP_LINE; do
  PAPP=""
  if [ "$(echo "$PAPP_LINE" | awk '{ print NF }')" -gt 1 ]
  then
    PAPP="$(echo "$PAPP_LINE" | awk '{ print $2 }')"
  else
    PAPP=$PAPP_LINE
  fi

  OUTPUT=$("$SNAP_DIR$PAPP" drivers "${ARGS[@]}")
  echo "$PAPP: $OUTPUT"
done < printer-apps.txt
