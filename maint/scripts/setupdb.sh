#!/bin/sh

# This will return a single line with all necessary params in a 'key=value' form
CONF=`cd ../../foomatic &&  php ../dump_mysql_config.php | sed 's/: /=/g'`
# This will export 'server', 'user', 'password' and 'database' variables
export $CONF

DBPARAMS="--password=$password -u $user -h $server"

mysql $DBPARAMS -e "DROP DATABASE IF EXISTS $database"
mysql $DBPARAMS -e "CREATE DATABASE $database"

# Preserver correct table order so foreign key can be created!
for i in \
    CreateDriverTable \
    CreateDriverDependency \
    CreateDriverPackagesTable \
    CreateDriverPrinterAssociationTable \
    CreateDriverPrinterAssociationTranslationTable \
    CreateDriverSupportContactsTable \
    CreateDriverSupportContactsTranslationTable \
    CreateDriverTranslationTable \
    CreatePrinterTable \
    CreatePrinterTranslationTable \
do
    echo Processing ${i}.sql
    mysql $DBPARAMS $database < ${i}.sql
done

