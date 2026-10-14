<?php

use Illuminate\Support\Facades\Route;
use MikhailMelnikov\BackpackJobsMonitor\Http\Controllers\QueueJobsController;
use MikhailMelnikov\BackpackJobsMonitor\Http\Controllers\ScheduledJobsController;

/*
|--------------------------------------------------------------------------
| MikhailMelnikov\BackpackJobsMonitor Routes
|--------------------------------------------------------------------------
|
| Scheduler and queue pages inside the Backpack admin panel.
|
*/

if (! config('mikhailmelnikov.backpack-jobs-monitor.setup_routes', true)) {
    return;
}

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin') . '/' . config('mikhailmelnikov.backpack-jobs-monitor.route_prefix', 'jobs'),
    'middleware' => array_merge(
        ['web', config('backpack.base.middleware_key', 'admin')],
        (array) config('mikhailmelnikov.backpack-jobs-monitor.middleware', [])
    ),
    'as' => 'jobs-monitor.',
], function (): void {
    Route::get('schedule', [ScheduledJobsController::class, 'index'])->name('schedule');
    Route::post('schedule/{task}/run', [ScheduledJobsController::class, 'run'])
        ->where(['task' => '[0-9a-f]{32}'])
        ->name('schedule.run');

    Route::get('queue', [QueueJobsController::class, 'index'])->name('queue');
    Route::post('queue/{id}/delete', [QueueJobsController::class, 'destroyQueued'])
        ->where(['id' => '[0-9]+'])
        ->name('queue.delete');

    Route::post('failed/retry-all', [QueueJobsController::class, 'retryAllFailed'])->name('failed.retry-all');
    Route::post('failed/flush', [QueueJobsController::class, 'flushFailed'])->name('failed.flush');
    Route::post('failed/{uuid}/retry', [QueueJobsController::class, 'retryFailed'])->name('failed.retry');
    Route::post('failed/{uuid}/delete', [QueueJobsController::class, 'destroyFailed'])->name('failed.delete');
});
