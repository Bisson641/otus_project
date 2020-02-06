# otus-project
Требования к реализации:

— Ansible-роли для развёртывания (под Vagrant, реальные сервера)

— Vagrant-стенд

В итоге в проект должны быть включены:
— как минимум 2 узла с СУБД; 

— минимум 2 узла с веб-серверами; 

— настройка межсетевого экрана (запрещено всё, что не разрешено); 

— скрипты резервного копирования; 

— центральный сервер сбора логов (Rsyslog/Journald/ELK). 

# Описание хостов

* proxy1 - балансировка web1, web2
* proxy2 - резервный балансировщик (горячий резерв)
* web1 - web-сервер
* web2 - web-сервер
* db1, db2 - БД, здесь нет мастера и слейва. db2 является горячей копией db1. В прокси настроено так, что в случае отказа db1 подхватится db2
* logger - настроен rsyslog от proxy-, web- и db-серверов (только error, emerg, crit)
* backup - сервер резервного копирования, использует bacula

Синхронизация между веб-серверами настроена с помощью rsync + lsync
# Работоспособность

Поднимаем кластер `vagrant up && ansible-playbook cluster.yml`

Пробросим порт 80 c proxy1 на на домашнюю ОС `sudo ssh -N -L 8080:192.168.10.100:80 vagrant@127.0.0.1 -p 2222` (P.S. пока не понял почему не открывается сама страница [http://192.168.10.100/wordpress](http://192.168.10.100/wordpress))

Перейдем по ссылке для и откроем установщик [wordpress](http://127.0.0.1:8080/wordpress)
