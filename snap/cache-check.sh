#!/bin/bash
cd /var/www/openprinting.org/openprinting/snap || exit
exec 2>>error.log

if [ ! -f printer-apps.txt ]; then
  exit
fi

if [ ! -f applist-version.txt ]; then
  touch applist-version.txt
fi

# Check snap status
snap list > snap-tester.txt
if [ ! -s snap-tester.txt ]; then
  rm -f snap-tester.txt
  exit
fi
rm -f snap-tester.txt

# Get app versions
touch applist-version-new.txt

while read -r package_line; do
  package=""
  if [ "$(echo "$package_line" | awk '{ print NF }')" -gt 1 ]; then
    package="$(echo "$package_line" | awk '{ print $1 }')"
  else
    package="$package_line"
  fi

  snap list | grep "$package" | awk '{ print $2, $3 }' >> applist-version-new.txt
done < printer-apps.txt

# If there are changes, clear the cache
diff -u applist-version.txt applist-version-new.txt > applist-version.diff
rm -f applist-version-new.txt
if [ -s applist-version.diff ]; then
  rm -f cache/*.out
fi

rm -f applist-version.diff

