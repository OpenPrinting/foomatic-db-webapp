#!/bin/bash
cd /var/www/openprinting.org/openprinting/snap || exit
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

while read -r PAPP; do
  OUTPUT=$("$SNAP_DIR$PAPP" drivers "${ARGS[@]}")
  echo "$PAPP: $OUTPUT"
done < printer-apps.txt
