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

Управление кластером осуществляется с помощью pacemaker + corosync
# Работоспособность

Поднимаем кластер `vagrant up && ansible-playbook cluster.yml`

Пробросим порт 80 c proxy1 на на домашнюю ОС `sudo ssh -N -L 8080:192.168.10.100:80 vagrant@127.0.0.1 -p 2222` (P.S. пока не понял почему не открывается сама страница [http://192.168.10.100/wordpress](http://192.168.10.100/wordpress))

**Перейдем по ссылке для и откроем установщик [wordpress](http://127.0.0.1:8080/wordpress)**

# Проверки

<details>
  <summary>Проверка балансировки прокси</summary>
  
  Проверяем порты на первой ноде и на второй. Выключаем первую ноду. Проверяем порты на второй. Видим, что IP балансировки переехал на вторую ноду.
  
  ![1](https://github.com/mariosmolov/otus-project/blob/master/img/2020-02-07_08-19-50.png)

  ![2](https://github.com/mariosmolov/otus-project/blob/master/img/2020-02-07_08-20-13.png)
  
  После перезагрузки первой ноды IP вернулся на первую ноду
  
  ![3](https://github.com/mariosmolov/otus-project/blob/master/img/2020-02-07_08-36-53.png)
</details>
