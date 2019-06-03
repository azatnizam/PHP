###   Система хранения событий для аналитики
   Аналитик хочет иметь систему со следующими возможностями:
   1) Система должна хранить события, которые в последующем будут отправляться сервису событий
   2) События характеризуются важностью (аналитик готов выставлять важность в целых числах)
   3) События характеризуются критериями возникновения. Событие возникает только если выполнены все критерии его возникновения. Для простоты все критерии заданы так: <критерий>=<значение>
   
   Таким образом предположим, что аналитик заносит в систему следующие события:
   ```
   {
   priority: 1000,
   conditions: {
   param1 = 1
   },
   event: {
   ::event::
   },
   },
   {
   priority: 2000,
   conditions: {
   param1 = 2,
   param2 = 2
   },
   event: {
   ::event::
   },
   },
   {
   priority: 3000,
   conditions: {
   param1 = 1,
   param2 = 2
   },
   event: {
   ::event::
   },
   },
   ```
   
   От пользователя приходит запрос:
   ```
   {
   params: {
   param1 = 1,
   param2 = 2
   }
   }
   ```
   
   Под этот запрос подходят первая и третья запись, т.к. в них обеих выполнены все условия, но приоритетнее третья, так как имеет больший priority.
   
   Написать систему, которая будет уметь:
   1) добавлять новое событие в систему хранения событий
   2) очищать все доступные события
   3) отвечать на запрос пользователя наиболее подходящим событием
   4) использовать для хранения событий redis
   Критерии оценки: Максимальная оценка: 10 баллов.
   5 баллов за реализацию хранения событий в редисе и реализацию метода добавления новых событий в систему
   1 балл за метод очистки всех доступных событий
   4 балла за метод поиска для запроса пользователя наиболее подходящего события