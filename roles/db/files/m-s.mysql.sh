read db1file db1position <<< $(mysql -h 192.168.1.21 -uroot -p\!Qaz2wsx -e "show master status;" | grep mysql | awk '{print $1" "$2}')
read db2file db2position <<< $(mysql -h 192.168.1.22 -uroot -p\!Qaz2wsx -e "show master status;" | grep mysql | awk '{print $1" "$2}')

pass="!Qaz2wsx"
command1="mysql -h 192.168.1.21 -uroot -p$pass "
command2="mysql -h 192.168.1.22 -uroot -p$pass "

$command1 "-e CHANGE MASTER TO MASTER_HOST='192.168.1.22', MASTER_USER='replica', MASTER_PASSWORD='replicapasswd', MASTER_LOG_FILE = '$db2file', MASTER_LOG_POS = $db2position;"
$command2 "-e CHANGE MASTER TO MASTER_HOST='192.168.1.21', MASTER_USER='replica', MASTER_PASSWORD='replicapasswd', MASTER_LOG_FILE = '$db1file', MASTER_LOG_POS = $db1position;"

$command1 "-e start slave;"
$command2 "-e start slave;"

echo $db1file
echo $db1position
echo $db2file
echo $db2position

