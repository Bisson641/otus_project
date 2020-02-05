#!/bin/sh

/usr/libexec/bacula/create_mysql_database -u root
/usr/libexec/bacula/grant_mysql_privileges
/usr/libexec/bacula/make_mysql_tables -u bacula
