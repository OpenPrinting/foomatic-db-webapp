#!/bin/bash
cd /var/www/openprinting.org/openprinting/snap || exit
exec 2>>error.log
SNAP_DIR=/var/lib/snapd/snap/bin/

if [ ! -f printer-apps.txt ]; then
  exit
fi

if [ ! -d cache ]; then
  mkdir cache
fi

ARGS=()
DEVICE_ID=""

for ARG in "$@"; do
  KEY=${ARG%%=*}
  VALUE=${ARG#*=}
  ARGS+=(-o "$KEY=\"$VALUE\"")
  
  if [ "$KEY" = "device-id" ]; then
    DEVICE_ID="$VALUE"
  fi
done

# Fall back to snap if no cache
if [ ! -f "cache/$DEVICE_ID.out" ]; then
  while read -r PAPP_LINE; do
    PAPP=""
    if [ "$(echo "$PAPP_LINE" | awk '{ print NF }')" -gt 1 ]
    then
      PAPP="$(echo "$PAPP_LINE" | awk '{ print $2 }')"
    else
      PAPP=$PAPP_LINE
    fi

    OUTPUT=$("$SNAP_DIR$PAPP" drivers "${ARGS[@]}")
    echo "$PAPP: $OUTPUT" >> "cache/$DEVICE_ID.out"
  done < printer-apps.txt
fi

# Write out cache contents
cat "cache/$DEVICE_ID.out"

