# Backpack Jobs Monitor

Аддон для [Backpack for Laravel](https://backpackforlaravel.com), который показывает в админке **планировщик** Laravel и **очередь на базе БД**:

- страница **«Планировщик»** — все задачи с расписанием в понятном виде и временем следующего запуска; ручной запуск задачи: *В очередь* (отправить её job в очередь) или *Сейчас* (выполнить прямо в запросе);
- страница **«Очередь»** — счётчики ожидающих / выполняющихся / отложенных / упавших задач, список задач в очереди (удаление), упавшие задачи с ошибкой, стектрейсом, payload и адресом получателя уведомления (повтор, повтор всех, удаление, очистка).

Вся логика находится в ядре [`mikhailmelnikov/laravel-jobs-monitor`](https://github.com/MikhailMelnikov/laravel-jobs-monitor), которое не зависит от админки; этот пакет — интерфейс для Backpack поверх него.

## Требования

- PHP 8.3+, Laravel 12 или 13
- Backpack CRUD 6.x или 7.x (рекомендуется тема Tabler)
- для страницы «Очередь» — драйвер очереди `database`

## Установка

### 1. Установите пакет

```bash
composer require mikhailmelnikov/backpack-jobs-monitor
```

Ядро `mikhailmelnikov/laravel-jobs-monitor` установится автоматически как зависимость. Сервис-провайдеры подключаются через package discovery — регистрировать их вручную не нужно.

### 2. Подготовьте таблицы очереди

Страница «Очередь» читает таблицы `jobs` и `failed_jobs`. В новом приложении Laravel они уже есть в стандартных миграциях. Если их нет:

```bash
php artisan make:queue-table
php artisan make:queue-failed-table
php artisan migrate
```

В `.env` должен быть указан драйвер `database`:

```dotenv
QUEUE_CONNECTION=database
```

Если основная очередь работает на другом драйвере, укажите нужное подключение в `queue_connection` конфига ядра (см. ниже). Страница «Планировщик» работает с любым драйвером.

### 3. Добавьте пункты в меню (необязательно)

```bash
php artisan backpack-jobs-monitor:install
```

Команда добавит в `resources/views/vendor/backpack/ui/inc/menu_items.blade.php` выпадающий пункт меню. Его можно вставить и вручную:

```blade
<x-backpack::menu-dropdown :title="trans('mikhailmelnikov.backpack-jobs-monitor::ui.menu.title')" icon="la la-tasks">
    <x-backpack::menu-dropdown-item :title="trans('mikhailmelnikov.backpack-jobs-monitor::ui.schedule.title')" icon="la la-clock" :link="route('jobs-monitor.schedule')" />
    <x-backpack::menu-dropdown-item :title="trans('mikhailmelnikov.backpack-jobs-monitor::ui.queue.title')" icon="la la-stream" :link="route('jobs-monitor.queue')" />
</x-backpack::menu-dropdown>
```

### 4. Ограничьте доступ (рекомендуется)

По умолчанию страницы доступны любому пользователю Backpack. Чтобы пустить только администраторов, опубликуйте конфиг и добавьте свой middleware:

```bash
php artisan vendor:publish --provider="MikhailMelnikov\BackpackJobsMonitor\AddonServiceProvider" --tag=config
```

```php
// config/mikhailmelnikov/backpack-jobs-monitor.php
'middleware' => [\App\Http\Middleware\CheckIfAdmin::class],
```

### 5. Проверьте

Страницы доступны по адресам **admin/jobs/schedule** и **admin/jobs/queue** (префикс `admin` берётся из `backpack.base.route_prefix`). Язык интерфейса определяется локалью приложения (`APP_LOCALE`): в комплекте английский и русский.

Если в проекте включён кеш маршрутов или конфига, сбросьте его:

```bash
php artisan optimize:clear
```

## Настройка

`config/mikhailmelnikov/backpack-jobs-monitor.php`:

| Ключ | По умолчанию | Назначение |
|---|---|---|
| `setup_routes` | `true` | Регистрировать маршруты. Укажите `false`, чтобы описать свои маршруты к контроллерам аддона. |
| `route_prefix` | `jobs` | Путь внутри префикса Backpack. |
| `middleware` | `[]` | Дополнительные middleware поверх `web` и admin-middleware Backpack, например проверка роли: `[\App\Http\Middleware\CheckIfAdmin::class]`. |

Настройки ядра (подключение очереди, размер страницы, атрибут с адресом получателя и т. д.) находятся в `config/jobs-monitor.php` — см. [README ядра](https://github.com/MikhailMelnikov/laravel-jobs-monitor#configuration). Опубликовать его можно так:

```bash
php artisan vendor:publish --tag=jobs-monitor-config
```

Ручные действия записываются в лог с id пользователя Backpack (`backpack_auth()`).

## Переопределение

```bash
# шаблоны → resources/views/vendor/mikhailmelnikov/backpack-jobs-monitor
php artisan vendor:publish --provider="MikhailMelnikov\BackpackJobsMonitor\AddonServiceProvider" --tag=views

# переводы → lang/vendor/mikhailmelnikov.backpack-jobs-monitor
php artisan vendor:publish --provider="MikhailMelnikov\BackpackJobsMonitor\AddonServiceProvider" --tag=lang
```

## Безопасность

Запуск задач планировщика и очистка упавших задач — мощные действия. По умолчанию страницы доступны всем пользователям Backpack — при необходимости ограничьте доступ через опцию `middleware`.

## Тесты

```bash
composer install
vendor/bin/pest
```

## Лицензия

MIT. См. [LICENSE.md](LICENSE.md).
