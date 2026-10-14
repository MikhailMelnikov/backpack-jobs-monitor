<?php

declare(strict_types=1);

namespace MikhailMelnikov\BackpackJobsMonitor;

use Illuminate\Support\ServiceProvider;
use MikhailMelnikov\BackpackJobsMonitor\Console\InstallCommand;
use MikhailMelnikov\JobsMonitor\Contracts\ActorResolver;

/**
 * Backpack add-on for the jobs monitor core.
 *
 * Config, views, translations and routes are loaded by {@see AutomaticServiceProvider}
 * from the conventional directories, as in every Backpack add-on.
 */
class AddonServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider;

    /**
     * Vendor part of the view, translation and config namespace.
     *
     * @var string
     */
    protected $vendorName = 'mikhailmelnikov';

    /**
     * Package part of the view, translation and config namespace.
     *
     * @var string
     */
    protected $packageName = 'backpack-jobs-monitor';

    /**
     * Artisan commands of the add-on.
     *
     * @var array<int, class-string>
     */
    protected $commands = [InstallCommand::class];

    /**
     * Register the add-on services.
     */
    public function register(): void
    {
        $this->autoregister();

        // manual actions are attributed to the Backpack admin, not to the default guard user
        $this->app->bind(ActorResolver::class, BackpackActorResolver::class);
    }
}
