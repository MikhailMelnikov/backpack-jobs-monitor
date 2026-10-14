<?php

declare(strict_types=1);

namespace MikhailMelnikov\BackpackJobsMonitor\Console;

use Illuminate\Console\Command;

/**
 * Adds the add-on pages to the Backpack sidebar menu.
 */
class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack-jobs-monitor:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add the Scheduler and Queue pages to the Backpack menu';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // a single line: add-menu-content skips the entry when an identical line already exists
        return $this->call('backpack:add-menu-content', ['code' => self::menuCode()]);
    }

    /**
     * Blade code of the menu dropdown.
     */
    public static function menuCode(): string
    {
        $ns = 'mikhailmelnikov.backpack-jobs-monitor::ui';

        return '<x-backpack::menu-dropdown :title="trans(\'' . $ns . '.menu.title\')" icon="la la-tasks">'
            . '<x-backpack::menu-dropdown-item :title="trans(\'' . $ns . '.schedule.title\')" icon="la la-clock" :link="route(\'jobs-monitor.schedule\')" />'
            . '<x-backpack::menu-dropdown-item :title="trans(\'' . $ns . '.queue.title\')" icon="la la-stream" :link="route(\'jobs-monitor.queue\')" />'
            . '</x-backpack::menu-dropdown>';
    }
}
