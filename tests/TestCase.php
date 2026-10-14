<?php

declare(strict_types=1);

namespace MikhailMelnikov\BackpackJobsMonitor\Tests;

use Backpack\Basset\BassetServiceProvider;
use Backpack\CRUD\BackpackServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use MikhailMelnikov\BackpackJobsMonitor\AddonServiceProvider;
use MikhailMelnikov\JobsMonitor\JobsMonitorServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Prologue\Alerts\AlertsServiceProvider;

/**
 * Base test case: Backpack, the core and the add-on on top of a bare Laravel app.
 */
abstract class TestCase extends Orchestra
{
    /**
     * Package providers to register.
     *
     * @param \Illuminate\Foundation\Application $app Application
     *
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            AlertsServiceProvider::class,
            BassetServiceProvider::class,
            BackpackServiceProvider::class,
            JobsMonitorServiceProvider::class,
            AddonServiceProvider::class,
        ];
    }

    /**
     * Configure the environment: sqlite, database queue, no admin auth middleware.
     *
     * @param \Illuminate\Foundation\Application $app Application
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.locale', 'en');
        $app['config']->set('app.key', 'base64:' . base64_encode(str_repeat('a', 32)));
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('queue.default', 'database');
        $app['config']->set('queue.failed', [
            'driver' => 'database-uuids',
            'database' => 'testing',
            'table' => 'failed_jobs',
        ]);
        // the application's CheckIfAdmin middleware does not exist in testbench
        $app['config']->set('backpack.base.middleware_class', [\MikhailMelnikov\BackpackJobsMonitor\Tests\Fixtures\PassThrough::class]);
    }

    /**
     * Create the queue tables.
     */
    protected function defineDatabaseMigrations(): void
    {
        Schema::create('jobs', static function (Blueprint $table): void {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('failed_jobs', static function (Blueprint $table): void {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }
}
