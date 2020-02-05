#!/bin/sh

touch /tmp/bacula.sql
chown bacula:bacula /tmp/bacula.sql
chmod 600 /tmp/bacula.sql
mysqldump -ubacula -pbacula bacula > /tmp/bacula.sql


 
