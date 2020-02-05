#!/bin/sh

pass=`grep 'temporary password is generated' /var/log/mysqld.log | grep -oE '[^ ]+$'`

command="mysql --connect-expired-password -uroot -p$pass -e "
$command "alter user 'root'@'localhost' identified by '!Qaz2wsx';"

pass="!Qaz2wsx"
command="mysql --connect-expired-password -uroot -p$pass "

$command "-e install component 'file://component_log_sink_syseventlog';"
$command "-e SET PERSIST log_error_services = 'log_filter_internal; log_sink_syseventlog';"

$command "-e update mysql.user set host='%' where user='root';"
$command "-e set persist validate_password.policy=0;"
$command "-e create database wordpress;"
$command "-e create user 'wordpress'@'%' identified by 'wordpress';"
$command "-e grant all privileges on wordpress.* to 'wordpress'@'%';"

$command "-e create user 'replica'@'%' identified by 'replicapasswd';"
$command "-e grant replication slave on *.* to 'replica'@'%';"

