#!/bin/bash

pcs cluster auth -uhacluster -phacluster proxy1 proxy2 web1 web2 db1 db2
pcs cluster setup --name otus-proxy proxy1 proxy2 web1 web2 db1 db2
pcs cluster enable --all
pcs cluster start --all
pcs property set symmetric-cluster=false
pcs property set stonith-enabled=false

pcs resource create proxy_vip ocf:heartbeat:IPaddr2 ip=192.168.10.100 cidr_netmask=32 op monitor interval=10s
pcs constraint location proxy_vip prefers proxy1=100 proxy2=50
pcs resource create proxy_service systemd:haproxy op monitor interval=10s
pcs constraint location proxy_service prefers proxy1=100 proxy2=50
pcs constraint colocation set proxy_vip proxy_service
pcs constraint order start proxy_vip then proxy_service

pcs resource create web_apache systemd:httpd
pcs resource clone web_apache
pcs constraint location web_apache-clone prefers web1 web2

pcs resource create mysql_vip ocf:heartbeat:IPaddr2 ip=192.168.1.20 cidr_netmask=32 op monitor interval=10s
pcs constraint location mysql_vip prefers db1=100 db2=50
pcs resource create mysql_service systemd:mysqld
pcs resource clone mysql_service
pcs constraint location mysql_service-clone prefers db1 db2
pcs constraint colocation add mysql_vip mysql_service-clone
