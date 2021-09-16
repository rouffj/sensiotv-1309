#!/bin/sh
php build/build_phar.php
chmod +x build/omdb.phar
cp build/omdb.phar /usr/local/bin/omdb
echo "omdb.phar file compiled + accessible from commandline as /usr/local/bin/omdb !"

