Задание:  
Клиент для вашей библиотеки должен работать по протоколу HTTP.  
Для этого, используя docker compose, вы создадите два контейнера, один с nginx, а второй с php-fpm и приложением, использующим библиотеку.

Решение:  
в папку code с помощью composer установил созданное на уроке 4 библиотеку.  
папка code  подключается как volume к контейнеру  
docker-compose содержит 2 контейнера: nginx и php-fpm

далее конфиг для локального сайта mysite.local,

коннект на http://mysyte.local:8080 дает мне ответ из библиотеки:
Hello from Alex