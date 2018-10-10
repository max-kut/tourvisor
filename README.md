# tourvisor.ru API library

PHP обертка для работы с API [tourvisor.ru](https://tourvisor.ru "tourvisor.ru")

Документация по API находится на [диске](https://drive.google.com/drive/folders/0B1Lc2hczO1lCZ2VPNlpVcGItZXc "диске"). 

Библиотека еще в разработке. Не стесняйтесь создавать issue

## Установка
Установка в проект осуществляется при помощи **composer**: 

`$ composer require maxkut/tourvisor`

Минимальные системные требования:
`php: ^7.1.3`

## Использование

Логика работы с библиотекой проста - создается один объект ядра библиотеки с http клиентом, который имеет метод `getResults`, принимающий единственный параметр - объект запроса из `Tourvisor\Requests`. В каждом классе запроса есть docBlock с описанием параметров, которые можно передать в запрос.

```php
use Tourvisor\Tourvisor;
use Tourvisor\Client;

// создаем объект ядра библиотеки с http клиентом
$tourvisor = new Tourvisor(new Client('login-in@tourvisor.ru', 'password'));
// ...
```

Создание поискового запроса:
```php
use Tourvisor\Requests\SearchRequest;

// ... $tourvisor ...

$searchRequest = new SearchRequest();
$searchRequest->country = 30;
$searchRequest->departure = 1;
// остальные параметры можно узнать в docBlock класса запроса

$result = $tourvisor->getResult($searchRequest); 
// В ответ придет идентификатор запроса, например 1015951847
```

Получение результатов поискового запроса:
```php
use Tourvisor\Requests\SearchResultRequest;

// ... $tourvisor ...

$searchResultRequest = new SearchResultRequest();
// передаем обязательный параметр - идентификатор запроса
$searchResultRequest->requestid = 1015951847;

$result = $tourvisor->getResult($searchResultRequest);
```

## Интеграции
#### Laravel >= 5.5
Библиотека использует автоматическую регистрацию сервис-провайдера.

перед использованием добавльте две переменные в `.env` файл:
```
TOURVISOR_LOGIN="login-in@tourvisor.ru"
TOURVISOR_PASSWORD="password"
```

Использование сервиса в контроллере
```php
    /**
     * @param \Tourvisor\Tourvisor $tourvisor
     * @param \Tourvisor\Requests\HotToursRequest $hotToursRequest
     * @throws \Tourvisor\Exceptions\AuthorizeException
     * @throws \Tourvisor\Exceptions\HasEmptyRequiredParamsException
     * @throws \Tourvisor\Exceptions\ResponseException
     */
    public function index(\Tourvisor\Tourvisor $tourvisor, \Tourvisor\Requests\HotToursRequest $hotToursRequest)
    {
        $hotToursRequest->items = 10;
        $hotToursRequest->city = 1;
        
        $result = $tourvisor->getResult($hotToursRequest);
        
        return response($result);
    }
```
Либо можно извлечь сервис из любого места приложения при помощи сервис-контейнера
```php
$tourvisor = app('tourvisor');
// или
$tourvisor = app()->make('tourvisor');
```
Или использовать фасад `Tourvisor`:
```php
// ... сформировали $searchRequest

$result = \Tourvisor::getResult($searchRequest);
```

### Помощь в разработке
- Fork в свой аккаунт.
- Редактирование
- pull-request