# Задание

Навыки работы с NoSQL  
Цель: Научится создавать приложение для анализа каналов на Youtube. Научится писать систему.

Создать приложение для анализа каналов на Youtube:
1. Создать структуру/структуры хранения информации о канале и видео канала в mongoDB, описать в виде JSON с указанием типов полей.
Описать какие индексы понадобятся в данной структуре?
2. Создать необходимые модели для добавления и удаления данных из коллекций
3. Реализовать класс статистики, который может возвращать:
- Суммарное кол-во лайков и дизлайков для канала по всем его видео
- Топ N каналов с лучшим соотношением кол-во лайков/кол-во дизлайков

4*. Можно создать паука, который будет ходить по Youtube и наполнять базу данными

Критерии оценки:
1. Желательно параллельно попрактиковаться и выполнить ДЗ в других NoSQL хранилищах
2. Слой кода, отвечающий за работу с хранилищем должен позволять легко менять хранилище

# Использование

### Подготовка

```bash
docker volume create mongo_data
docker-compose build
docker-compose up -d
docker-compose exec composer install --no-dev
```

Также нужно создать .env файл и задать в нем переменную APP_KEY_YOUTUBE.  
Значение переменной - ключ API для работы с YouTube, подробнее пункты 1-3 по ссылке:  
https://developers.google.com/youtube/v3/getting-started

### Поиск и запись в базу данных информации
Вместо 'php' можно указать любую другую строку.
```bash
docker-compose exec app ./index.php fill 'php'
```

### Добавим индекс
```bash
docker-compose exec app ./index.php index
```

### Получим лайки/дизлайки канала
Только по каналу и видео, которые есть в базе данных.
```bash
docker-compose exec app ./index.php chan UCBB1kqYMWUrSxrQkq8BYzZA
```

### Топ каналов по соотношению лайки/дизлайки
```bash
docker-compose exec app ./index.php top 5
```
