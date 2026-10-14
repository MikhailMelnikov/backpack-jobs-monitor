<?php

declare(strict_types=1);

namespace MikhailMelnikov\BackpackJobsMonitor\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use MikhailMelnikov\JobsMonitor\UseCase\DeleteFailedJobHandler;
use MikhailMelnikov\JobsMonitor\UseCase\DeleteQueuedJobHandler;
use MikhailMelnikov\JobsMonitor\UseCase\FlushFailedJobsHandler;
use MikhailMelnikov\JobsMonitor\UseCase\GetFailedJobsHandler;
use MikhailMelnikov\JobsMonitor\UseCase\GetQueuedJobsHandler;
use MikhailMelnikov\JobsMonitor\UseCase\GetQueueOverviewHandler;
use MikhailMelnikov\JobsMonitor\UseCase\RetryFailedJobsHandler;

/**
 * Queue page: what is waiting for a worker and what has failed.
 *
 * Replaces `php artisan queue:failed`, `queue:retry`, `queue:forget` and `queue:flush`.
 */
class QueueJobsController extends Controller
{
    use RedirectsWithAlert;
    use ResolvesHandlers;

    /**
     * Page with the queue summary, queued jobs and failed jobs.
     */
    public function index(): View
    {
        return view('mikhailmelnikov.backpack-jobs-monitor::queue', [
            'title' => trans('mikhailmelnikov.backpack-jobs-monitor::ui.queue.title'),
            'overview' => $this->handler(GetQueueOverviewHandler::class)->handle(),
            'queuedJobs' => $this->handler(GetQueuedJobsHandler::class)->handle(),
            'failedJobs' => $this->handler(GetFailedJobsHandler::class)->handle(),
        ]);
    }

    /**
     * Delete a job waiting in the queue.
     *
     * @param int $id Record id in the jobs table
     */
    public function destroyQueued(int $id): RedirectResponse
    {
        return $this->perform(fn (): string => $this->handler(DeleteQueuedJobHandler::class, ['id' => $id])->handle());
    }

    /**
     * Push a failed job back to the queue.
     *
     * @param string $uuid Failed job UUID
     */
    public function retryFailed(string $uuid): RedirectResponse
    {
        return $this->perform(fn (): string => $this->handler(RetryFailedJobsHandler::class, ['uuids' => [$uuid]])->handle());
    }

    /**
     * Push all failed jobs back to the queue.
     */
    public function retryAllFailed(): RedirectResponse
    {
        return $this->perform(fn (): string => $this->handler(RetryFailedJobsHandler::class)->handle());
    }

    /**
     * Delete a failed job record.
     *
     * @param string $uuid Failed job UUID
     */
    public function destroyFailed(string $uuid): RedirectResponse
    {
        return $this->perform(fn (): string => $this->handler(DeleteFailedJobHandler::class, ['uuid' => $uuid])->handle());
    }

    /**
     * Delete all failed job records.
     */
    public function flushFailed(): RedirectResponse
    {
        return $this->perform(fn (): string => $this->handler(FlushFailedJobsHandler::class)->handle());
    }
}
