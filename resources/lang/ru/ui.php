<?php

return [
    'menu' => [
        'title' => 'Задачи',
    ],

    'tabs' => [
        'schedule' => 'Планировщик',
        'queue' => 'Очередь',
    ],

    'action_failed' => 'Не удалось выполнить действие: :message',
    'task_failed' => 'Задача завершилась с ошибкой: :message',

    'schedule' => [
        'title' => 'Планировщик задач',
        'subtitle' => 'задачи из планировщика Laravel — аналог <code>php artisan schedule:list</code>',
        'help' => 'Задачи выполняются по расписанию командой <code>php artisan schedule:run</code>. '
            . 'Кнопка <b>В очередь</b> отправляет задачу в очередь <code>:connection</code> — результат появится, '
            . 'когда её заберёт воркер. Кнопка <b>Сейчас</b> выполняет задачу прямо в этом запросе: '
            . 'удобно для быстрых задач, но долгая задача может оборваться по таймауту веб-сервера.',
        'task' => 'Задача',
        'schedule' => 'Расписание',
        'next_run' => 'Следующий запуск',
        'manual_run' => 'Запуск вручную',
        'running' => 'выполняется',
        'without_overlapping' => 'без наложений',
        'on_one_server' => 'на одном сервере',
        'queue_button' => 'В очередь',
        'sync_button' => 'Сейчас',
        'sync_confirm' => 'Выполнить задачу «:name» прямо сейчас? Страница будет ждать её завершения.',
        'empty' => 'В планировщике нет задач.',
    ],

    'queue' => [
        'title' => 'Очередь задач',
        'subtitle' => 'очередь <code>:connection</code> — аналог <code>php artisan queue:failed</code>',
        'waiting' => 'Ожидают воркера',
        'reserved' => 'Выполняются',
        'delayed' => 'Отложены',
        'failed' => 'Упали',
        'by_queue' => 'По очередям:',
        'queued_title' => 'В очереди (:count)',
        'id' => '#',
        'job' => 'Задача',
        'queue' => 'Очередь',
        'attempts' => 'Попытки',
        'created_at' => 'Добавлена',
        'available_at' => 'Доступна с',
        'reserved_since' => 'в работе с :time',
        'delete_confirm' => 'Удалить задачу №:id из очереди?',
        'empty' => 'Очередь пуста.',
        'failed_title' => 'Упавшие задачи (:count)',
        'retry_all' => 'Повторить все',
        'retry_all_confirm' => 'Вернуть в очередь все упавшие задачи?',
        'flush' => 'Очистить список',
        'flush_confirm' => 'Удалить все записи об упавших задачах? Задачи не будут выполнены.',
        'recipient' => 'Получатель',
        'failed_at' => 'Упала',
        'error' => 'Ошибка',
        'stack_trace' => 'стектрейс',
        'retry' => 'Вернуть в очередь',
        'delete_failed' => 'Удалить запись',
        'delete_failed_confirm' => 'Удалить запись об упавшей задаче?',
        'failed_empty' => 'Упавших задач нет.',
    ],
];
