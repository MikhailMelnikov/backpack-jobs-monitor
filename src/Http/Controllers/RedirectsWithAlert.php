<?php

declare(strict_types=1);

namespace MikhailMelnikov\BackpackJobsMonitor\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use MikhailMelnikov\JobsMonitor\Exceptions\JobsMonitorException;
use Prologue\Alerts\Facades\Alert;
use Throwable;

/**
 * Runs an action of the core and shows its result as a Backpack alert.
 */
trait RedirectsWithAlert
{
    /**
     * Perform the action and redirect back with a success, warning or error alert.
     *
     * @param callable(): string $action      Action returning a success message
     * @param string             $errorPrefix Translation key of the unexpected error prefix
     */
    protected function perform(callable $action, string $errorPrefix = 'action_failed'): RedirectResponse
    {
        try {
            Alert::success($action())->flash();
        } catch (JobsMonitorException $exception) {
            Alert::warning($exception->getMessage())->flash();
        } catch (Throwable $exception) {
            report($exception);
            Alert::error(
                trans('mikhailmelnikov.backpack-jobs-monitor::ui.' . $errorPrefix, ['message' => $exception->getMessage()])
            )->flash();
        }

        return redirect()->back();
    }
}
