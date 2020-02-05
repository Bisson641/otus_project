#!/bin/sh

touch /tmp/wordpress.sql
chown bacula:bacula /tmp/wordpress.sql
chmod 600 /tmp/wordpress.sql
mysqldump -uwordpress -pwordpress wordpress > /tmp/wordpress.sql


 
