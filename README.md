# Отказоустойчивый кластер Wordpress
Требования к реализации:
* Ansible-роли для развёртывания (под Vagrant, реальные сервера)
* Vagrant-стенд

В итоге в проект должны быть включены:
* как минимум 2 узла с СУБД; 
* минимум 2 узла с веб-серверами; 
* настройка межсетевого экрана (запрещено всё, что не разрешено); 
* скрипты резервного копирования; 
* центральный сервер сбора логов (Rsyslog/Journald/ELK). 

### Схема

![schema](https://github.com/mariosmolov/otus-project/blob/master/img/2020-02-07_10-19-07.png)

### Описание хостов

* proxy1 - балансировка web1, web2
* proxy2 - резервный балансировщик (горячий резерв)
* web1 - web-сервер
* web2 - web-сервер
* db1, db2 - БД, здесь нет мастера и слейва. db2 является горячей копией db1. В прокси настроено так, что в случае отказа db1 подхватится db2
* logger - настроен rsyslog от proxy-, web- и db-серверов (только error, emerg, crit)
* backup - сервер резервного копирования, использует bacula

Синхронизация между веб-серверами настроена с помощью rsync + lsync

Управление кластером осуществляется с помощью pacemaker + corosync
### Работоспособность

Поднимаем кластер `vagrant up && ansible-playbook cluster.yml` (при выполнении плейбука может не ответить первая или вторая нода БД, это связано с малыми ресурсами в виде 1 ядра и 512 Мб оперативной памяти, но при повторном выполнении всё срабатывает)

Пробросим порт 80 c proxy1 на на домашнюю ОС `sudo ssh -N -L 8080:192.168.10.100:80 vagrant@127.0.0.1 -p 2222` (P.S. пока не понял почему не открывается сама страница [http://192.168.10.100/wordpress](http://192.168.10.100/wordpress))

**Перейдем по ссылке для и откроем установщик [wordpress](http://127.0.0.1:8080/wordpress)**

### Проверки

<details>
  <summary>Проверка балансировки прокси</summary>
  
  Проверяем порты на первой ноде и на второй. Выключаем первую ноду. Проверяем порты на второй. Видим, что IP балансировки переехал на вторую ноду.
  
  ![1](https://github.com/mariosmolov/otus-project/blob/master/img/2020-02-07_08-19-50.png)

  ![2](https://github.com/mariosmolov/otus-project/blob/master/img/2020-02-07_08-20-13.png)
  
  После перезагрузки первой ноды IP вернулся на первую ноду
  
  ![3](https://github.com/mariosmolov/otus-project/blob/master/img/2020-02-07_08-36-53.png)
</details>

<details>
  <summary>Проверка балансировки БД</summary>
  
  Проверяем порты на первой ноде и на второй. Выключаем первую ноду. Проверяем порты на второй.
  
  ![4](https://github.com/mariosmolov/otus-project/blob/master/img/2020-02-07_08-50-30.png)

  ![5](https://github.com/mariosmolov/otus-project/blob/master/img/2020-02-07_08-50-39.png)
  

</details>

<details>
  <summary>IPTABLES</summary>
  
  ```
  [vagrant@web1 ~]$ sudo iptables -L
Chain INPUT (policy ACCEPT)
target     prot opt source               destination
ACCEPT     all  --  anywhere             anywhere             ctstate RELATED,ESTABLISHED
ACCEPT     all  --  anywhere             anywhere
INPUT_direct  all  --  anywhere             anywhere
INPUT_ZONES_SOURCE  all  --  anywhere             anywhere
INPUT_ZONES  all  --  anywhere             anywhere
DROP       all  --  anywhere             anywhere             ctstate INVALID
REJECT     all  --  anywhere             anywhere             reject-with icmp-host-prohibited

Chain FORWARD (policy ACCEPT)
target     prot opt source               destination
ACCEPT     all  --  anywhere             anywhere             ctstate RELATED,ESTABLISHED
ACCEPT     all  --  anywhere             anywhere
FORWARD_direct  all  --  anywhere             anywhere
FORWARD_IN_ZONES_SOURCE  all  --  anywhere             anywhere
FORWARD_IN_ZONES  all  --  anywhere             anywhere
FORWARD_OUT_ZONES_SOURCE  all  --  anywhere             anywhere
FORWARD_OUT_ZONES  all  --  anywhere             anywhere
DROP       all  --  anywhere             anywhere             ctstate INVALID
REJECT     all  --  anywhere             anywhere             reject-with icmp-host-prohibited

Chain OUTPUT (policy ACCEPT)
target     prot opt source               destination
OUTPUT_direct  all  --  anywhere             anywhere

Chain FORWARD_IN_ZONES (1 references)
target     prot opt source               destination
FWDI_public  all  --  anywhere             anywhere            [goto]
FWDI_public  all  --  anywhere             anywhere            [goto]
FWDI_public  all  --  anywhere             anywhere            [goto]

Chain FORWARD_IN_ZONES_SOURCE (1 references)
target     prot opt source               destination

Chain FORWARD_OUT_ZONES (1 references)
target     prot opt source               destination
FWDO_public  all  --  anywhere             anywhere            [goto]
FWDO_public  all  --  anywhere             anywhere            [goto]
FWDO_public  all  --  anywhere             anywhere            [goto]

Chain FORWARD_OUT_ZONES_SOURCE (1 references)
target     prot opt source               destination

Chain FORWARD_direct (1 references)
target     prot opt source               destination

Chain FWDI_public (3 references)
target     prot opt source               destination
FWDI_public_log  all  --  anywhere             anywhere
FWDI_public_deny  all  --  anywhere             anywhere
FWDI_public_allow  all  --  anywhere             anywhere
ACCEPT     icmp --  anywhere             anywhere

Chain FWDI_public_allow (1 references)
target     prot opt source               destination

Chain FWDI_public_deny (1 references)
target     prot opt source               destination

Chain FWDI_public_log (1 references)
target     prot opt source               destination

Chain FWDO_public (3 references)
target     prot opt source               destination
FWDO_public_log  all  --  anywhere             anywhere
FWDO_public_deny  all  --  anywhere             anywhere
FWDO_public_allow  all  --  anywhere             anywhere

Chain FWDO_public_allow (1 references)
target     prot opt source               destination

Chain FWDO_public_deny (1 references)
target     prot opt source               destination

Chain FWDO_public_log (1 references)
target     prot opt source               destination

Chain INPUT_ZONES (1 references)
target     prot opt source               destination
IN_public  all  --  anywhere             anywhere            [goto]
IN_public  all  --  anywhere             anywhere            [goto]
IN_public  all  --  anywhere             anywhere            [goto]

Chain INPUT_ZONES_SOURCE (1 references)
target     prot opt source               destination

Chain INPUT_direct (1 references)
target     prot opt source               destination

Chain IN_public (3 references)
target     prot opt source               destination
IN_public_log  all  --  anywhere             anywhere
IN_public_deny  all  --  anywhere             anywhere
IN_public_allow  all  --  anywhere             anywhere
ACCEPT     icmp --  anywhere             anywhere

Chain IN_public_allow (1 references)
target     prot opt source               destination
ACCEPT     tcp  --  anywhere             anywhere             tcp dpt:ssh ctstate NEW
ACCEPT     tcp  --  anywhere             anywhere             tcp dpt:http ctstate NEW
ACCEPT     tcp  --  anywhere             anywhere             tcp dpt:bacula-fd ctstate NEW
ACCEPT     tcp  --  anywhere             anywhere             tcp dpt:efi-mg ctstate NEW
ACCEPT     tcp  --  anywhere             anywhere             tcp dpt:pcmk-remote ctstate NEW
ACCEPT     tcp  --  anywhere             anywhere             tcp dpt:hpoms-ci-lstn ctstate NEW
ACCEPT     udp  --  anywhere             anywhere             udp dpt:hpoms-dps-lstn ctstate NEW
ACCEPT     udp  --  anywhere             anywhere             udp dpt:netsupport ctstate NEW
ACCEPT     tcp  --  anywhere             anywhere             tcp dpt:9929 ctstate NEW
ACCEPT     udp  --  anywhere             anywhere             udp dpt:9929 ctstate NEW
ACCEPT     tcp  --  anywhere             anywhere             tcp dpt:21064 ctstate NEW

Chain IN_public_deny (1 references)
target     prot opt source               destination

Chain IN_public_log (1 references)
target     prot opt source               destination

Chain OUTPUT_direct (1 references)
target     prot opt source               destination
  ```

</details>

<details>
  <summary>Установка Wordpress</summary>
  
  Как говорилось выше, пробросим порт 80 c proxy1 на на домашнюю ОС `sudo ssh -N -L 8080:192.168.10.100:80 vagrant@127.0.0.1 -p 2222`.
  Перейдем по ссылке [wordpress](http://127.0.0.1:8080/wordpress)
  
  ![6](https://github.com/mariosmolov/otus-project/blob/master/img/2020-02-07_09-13-57.png)

  ![8](https://github.com/mariosmolov/otus-project/blob/master/img/2020-02-07_09-16-13.png)
  
  Попадем в админку и можем создавать страницы
  
  ![9](https://github.com/mariosmolov/otus-project/blob/master/img/2020-02-07_09-18-51.png)
  

</details>
