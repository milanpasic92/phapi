#!/usr/bin/env bash

wait-for-it.sh "$DB_HOST":"$DB_PORT" -t 30

mkdir -p .phalcon
chmod -R 777 .phalcon

mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" "-p$DB_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS $DB_NAME"

php vendor/phalcon/devtools/phalcon.php migration run --force --log-in-db

env >> /etc/environment

rm -rf /tmp
rm -rf /var/tmp

mkdir /tmp
mkdir /var/tmp

chmod go-rwx /var/tmp
chmod 1777 /tmp

mkdir /tmp_uploads
chmod -R 777 /tmp_uploads

touch /etc/cron.allow
echo "root" > /etc/cron.allow

/usr/sbin/apache2ctl -DFOREGROUND