#Установка и настройка
- Нужен PHP 7.4
- Нужен MySql(MariaDB) сервер 

Необходимо выполнить links.sql

В index.php находится константа settings
необходимо заполнить поля
- siteUrl - на основе этого параметра будет генерироваться ссылка
- секцию database необходимо заполнить

Требуется настроить url rewrite что-то типа 
``` \/(.*)$ => "index.php?link=$1 ```
#API
Скрипт принимает POST / GET запросы, притом GET используется только для редиректа на ссылку с подсчетом перехода

Для всех иных команд необходимо отправлять POST запрос в теле которого находится JSON объект с параметром:
- action - действие

и другими параметрами в зависимости от действия

Ответ придет в формате JSON объекта в котором обязательно указан:
- type - тип ответа который принимает значения ```success``` в случае успешного выполнения запроса, или ```error``` в случае ошибки
  - если вернулся тип ответа ```error``` при этом код ответа будет 500 и если ошибка не фатальная то будет присутствовать параметр ```message``` с описанием ошибки
    
###Пример успешного ответа
```json
    {
      "links": [
        {
          "url": "https://www.profi.ru",
          "visited": 1
        }
      ],
      "type": "success"
    }
```
###Пример ответа с ошибкой
```json
  {
    "message": "Bad request",
    "type": "error"
  }
```

##Порядок действий
Для того чтобы новый пользователь смог получать сокращенные ссылки и выполнять другие действия, необходимо выполнить регистрацию

В запросе должен присутствовать элемент ```action```

###Регистрация
Для регистрации необходимо отправить action ```register``` с обязательным параметром ```login```.
В качестве логина я предпологал использовать email но это не сильно важно, можно согласовать как фронтенд будет идентифицировать пользователя и исходя из этого изменить базу
```json
{
  "action": "register",
  "login": "some@mail.me"
}
```

В случае успеха в ответе вернется идентификатор пользователя в параметре ```user```, если же пользователь с таким логином уже существует, то регистрация не будет произведена, а в ответ просто вернется существующий идентификатор.

###Получение короткой ссылки
action - get
####Обязательные параметры
- url - исходная ссылка
- user - идентификатор пользователя
####Ответ
- link - короткая ссылка, сейчас она просто цифровая, но можно придумать какую либо сисиему хеширования

###Переход по ссылке
action - follow

Данное действие выполняется при любом GET запросе по-умолчанию, но так же его можно выполнить и в формате POST запроса.
####Обязательные параметры
- link - идентификатор короткой ссылки 
####Ответ
В случае успешного поиска записи в базе, система вернет заголовок запроса с перенаправлением через Location на требуемый URL.
При этом увеличится значение количества переходов по ссылке.

###Получение статистики
Запрос выдает список заведенных в систему ссылок и количество перехода по ним.
Так же возможно ограничить этот список по пользователю, и сделать постраничный вывод.
По-умолчанию выводятся 20 записей от всех пользователей.
####Обязательные параметры
Отсутствуют
####Необязательные параметры
- start - с какой позиции начинать отображения списка, значение по-умолчанию 0
- count - сколько записей показывать за один запрос, значение по-умолчанию 20
- user - идентификатор пользователя, если не указан, то выводятся все записи по всем пользователям
###Ответ
- links - массив данных, в котором в каждом элементе находятся параметры:
  - url - исходная ссылка
  - visited - количество переходов