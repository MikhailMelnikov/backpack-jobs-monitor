<?php

return [
    'menu' => [
        'title' => 'Jobs',
    ],

    'tabs' => [
        'schedule' => 'Scheduler',
        'queue' => 'Queue',
    ],

    'action_failed' => 'The action failed: :message',
    'task_failed' => 'The task failed: :message',

    'schedule' => [
        'title' => 'Scheduler',
        'subtitle' => 'Laravel scheduler tasks — like <code>php artisan schedule:list</code>',
        'help' => 'Tasks run on schedule via <code>php artisan schedule:run</code>. '
            . '<b>Push to queue</b> sends the task to the <code>:connection</code> queue — the result appears once a worker picks it up. '
            . '<b>Run now</b> executes the task right in this request: handy for quick tasks, '
            . 'but a long one may be cut off by the web server timeout.',
        'task' => 'Task',
        'schedule' => 'Schedule',
        'next_run' => 'Next run',
        'manual_run' => 'Manual run',
        'running' => 'running',
        'without_overlapping' => 'without overlapping',
        'on_one_server' => 'on one server',
        'queue_button' => 'Queue',
        'sync_button' => 'Now',
        'sync_confirm' => 'Run “:name” right now? The page will wait until it finishes.',
        'empty' => 'The scheduler has no tasks.',
    ],

    'queue' => [
        'title' => 'Queue',
        'subtitle' => 'connection <code>:connection</code> — like <code>php artisan queue:failed</code>',
        'waiting' => 'Waiting',
        'reserved' => 'Running',
        'delayed' => 'Delayed',
        'failed' => 'Failed',
        'by_queue' => 'By queue:',
        'queued_title' => 'Queued (:count)',
        'id' => '#',
        'job' => 'Job',
        'queue' => 'Queue',
        'attempts' => 'Attempts',
        'created_at' => 'Added',
        'available_at' => 'Available at',
        'reserved_since' => 'running since :time',
        'delete_confirm' => 'Remove job #:id from the queue?',
        'empty' => 'The queue is empty.',
        'failed_title' => 'Failed jobs (:count)',
        'retry_all' => 'Retry all',
        'retry_all_confirm' => 'Push all failed jobs back to the queue?',
        'flush' => 'Clear list',
        'flush_confirm' => 'Delete all failed job records? The jobs will not be executed.',
        'recipient' => 'Recipient',
        'failed_at' => 'Failed',
        'error' => 'Error',
        'stack_trace' => 'stack trace',
        'retry' => 'Push back to the queue',
        'delete_failed' => 'Delete record',
        'delete_failed_confirm' => 'Delete the failed job record?',
        'failed_empty' => 'No failed jobs.',
    ],
];
