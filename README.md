# Отказоустойчивый Wordpress
Требования к реализации:
* Ansible-роли для развёртывания (под Vagrant, реальные сервера)
* Vagrant-стенд

В итоге в проект должны быть включены:
* как минимум 2 узла с СУБД; 
* минимум 2 узла с веб-серверами; 
* настройка межсетевого экрана (запрещено всё, что не разрешено); 
* центральный сервер сбора логов (Rsyslog/Journald/ELK). 


### Описание хостов

* proxy1 - балансировка websrv1, websrv2
* zabbix - proxy для настройки zabbix
* websrv1 - web-сервер, DB server master
* websrv2 - web-сервер  DB server slave
* logger - настроен rsyslog  (только error, emerg, crit)
* alert - zabbix сервер
Синхронизация между веб-серверами настроена с помощью rsync + lsync

### Работоспособность

Поднимаем сервера  `vagrant up && ansible-playbook main.yml` 

Пароль root от БД `Qwerty123)`

**Перейдем по ссылке для и откроем установщик [wordpress](http://192.168.58.100:80/wordpress)**
