# Backpack Jobs Monitor

[Backpack for Laravel](https://backpackforlaravel.com) add-on that shows the Laravel **scheduler** and the **database queue** in your admin panel:

- **Scheduler** page — every task with a human-readable schedule and next run; run a task manually: *Queue* (push its job to the queue) or *Now* (execute in the request);
- **Queue** page — waiting / running / delayed / failed counters, queued jobs (delete), failed jobs with the error, stack trace, payload and the notification recipient (retry, retry all, delete, clear).

The logic lives in the admin-agnostic core [`mikhailmelnikov/laravel-jobs-monitor`](https://github.com/MikhailMelnikov/laravel-jobs-monitor); this package is the Backpack UI on top of it.

## Requirements

- PHP 8.3+, Laravel 12 or 13
- Backpack CRUD 6.x or 7.x (Tabler theme recommended)
- the `database` queue driver for the queue page

## Installation

``` bash
# install the package
composer require mikhailmelnikov/backpack-jobs-monitor

# [optional] add the menu items to the sidebar
php artisan backpack-jobs-monitor:install
```

The install command adds this to `resources/views/vendor/backpack/ui/inc/menu_items.blade.php` — you can paste it yourself instead:

```blade
<x-backpack::menu-dropdown :title="trans('mikhailmelnikov.backpack-jobs-monitor::ui.menu.title')" icon="la la-tasks">
    <x-backpack::menu-dropdown-item :title="trans('mikhailmelnikov.backpack-jobs-monitor::ui.schedule.title')" icon="la la-clock" :link="route('jobs-monitor.schedule')" />
    <x-backpack::menu-dropdown-item :title="trans('mikhailmelnikov.backpack-jobs-monitor::ui.queue.title')" icon="la la-stream" :link="route('jobs-monitor.queue')" />
</x-backpack::menu-dropdown>
```

The pages are available at **admin/jobs/schedule** and **admin/jobs/queue**.

## Configuration

``` bash
php artisan vendor:publish --provider="MikhailMelnikov\BackpackJobsMonitor\AddonServiceProvider" --tag=config
```

`config/mikhailmelnikov/backpack-jobs-monitor.php`:

| Key | Default | Meaning |
|---|---|---|
| `setup_routes` | `true` | Register the routes. Set `false` to define your own routes to the add-on controllers. |
| `route_prefix` | `jobs` | Path under the Backpack prefix. |
| `middleware` | `[]` | Extra middleware on top of Backpack's `web` + admin ones, e.g. a role check: `[\App\Http\Middleware\CheckIfAdmin::class]`. |

Core settings (queue connection, page size, recipient attribute, …) are in `config/jobs-monitor.php` — see the [core README](https://github.com/MikhailMelnikov/laravel-jobs-monitor#configuration).

Manual actions are written to the log with the id of the Backpack user (`backpack_auth()`).

## Overwriting

``` bash
# views → resources/views/vendor/mikhailmelnikov/backpack-jobs-monitor
php artisan vendor:publish --provider="MikhailMelnikov\BackpackJobsMonitor\AddonServiceProvider" --tag=views

# translations → lang/vendor/mikhailmelnikov.backpack-jobs-monitor
php artisan vendor:publish --provider="MikhailMelnikov\BackpackJobsMonitor\AddonServiceProvider" --tag=lang
```

## Security

Running scheduler tasks and flushing failed jobs are powerful actions. Every Backpack user can reach the pages by default — restrict them with the `middleware` option if needed.

## Testing

```bash
composer install
vendor/bin/pest
```

## License

MIT. See [LICENSE.md](LICENSE.md).
