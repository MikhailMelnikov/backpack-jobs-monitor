<?php

declare(strict_types=1);

namespace MikhailMelnikov\BackpackJobsMonitor\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use MikhailMelnikov\BackpackJobsMonitor\Http\Requests\RunScheduledTaskRequest;
use MikhailMelnikov\JobsMonitor\UseCase\CountFailedJobsHandler;
use MikhailMelnikov\JobsMonitor\UseCase\GetScheduledTasksHandler;
use MikhailMelnikov\JobsMonitor\UseCase\RunScheduledTaskHandler;

/**
 * Scheduler page: the task list and manual runs.
 *
 * Replaces `php artisan schedule:list` and `php artisan schedule:run`.
 */
class ScheduledJobsController extends Controller
{
    use RedirectsWithAlert;
    use ResolvesHandlers;

    /**
     * Page with the scheduler tasks.
     */
    public function index(): View
    {
        return view('mikhailmelnikov.backpack-jobs-monitor::schedule', [
            'title' => trans('mikhailmelnikov.backpack-jobs-monitor::ui.schedule.title'),
            'tasks' => $this->handler(GetScheduledTasksHandler::class)->handle(),
            'queueConnection' => (string) config('queue.default'),
            'failedCount' => $this->handler(CountFailedJobsHandler::class)->handle(),
        ]);
    }

    /**
     * Run a scheduler task manually.
     *
     * @param RunScheduledTaskRequest $request Request with the chosen run mode
     * @param string                  $task    Scheduler task identifier
     */
    public function run(RunScheduledTaskRequest $request, string $task): RedirectResponse
    {
        $mode = $request->mode();

        return $this->perform(
            fn (): string => $this->handler(RunScheduledTaskHandler::class, ['taskId' => $task, 'mode' => $mode])->handle(),
            'task_failed'
        );
    }
}
