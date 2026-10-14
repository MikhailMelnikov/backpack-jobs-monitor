<?php

declare(strict_types=1);

namespace MikhailMelnikov\BackpackJobsMonitor\Tests\Feature;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use MikhailMelnikov\BackpackJobsMonitor\BackpackActorResolver;
use MikhailMelnikov\BackpackJobsMonitor\Console\InstallCommand;
use MikhailMelnikov\BackpackJobsMonitor\Tests\TestCase;
use MikhailMelnikov\JobsMonitor\Contracts\ActorResolver;

/**
 * The add-on wiring: routes, bindings, translations and actions.
 */
class AddonTest extends TestCase
{
    /**
     * All pages and actions are registered under the Backpack prefix.
     */
    public function testRegistersRoutesUnderBackpackPrefix(): void
    {
        self::assertSame(url('admin/jobs/schedule'), route('jobs-monitor.schedule'));
        self::assertSame(url('admin/jobs/queue'), route('jobs-monitor.queue'));

        foreach (['schedule.run', 'queue.delete', 'failed.retry-all', 'failed.flush', 'failed.retry', 'failed.delete'] as $name) {
            self::assertTrue(Route::has('jobs-monitor.' . $name), $name);
        }

        $middleware = Route::getRoutes()->getByName('jobs-monitor.queue')?->gatherMiddleware() ?? [];
        self::assertContains('web', $middleware);
        self::assertContains(config('backpack.base.middleware_key', 'admin'), $middleware);
    }

    /**
     * Manual actions are attributed to the Backpack user.
     */
    public function testBindsBackpackActorResolver(): void
    {
        self::assertInstanceOf(BackpackActorResolver::class, $this->app->make(ActorResolver::class));
    }

    /**
     * UI strings come with the add-on in both languages.
     */
    public function testShipsTranslations(): void
    {
        self::assertSame('Queue', trans('mikhailmelnikov.backpack-jobs-monitor::ui.tabs.queue'));

        $this->app->setLocale('ru');
        self::assertSame('Очередь', trans('mikhailmelnikov.backpack-jobs-monitor::ui.tabs.queue'));
    }

    /**
     * The menu snippet points to the add-on routes.
     */
    public function testMenuCodeLinksToPages(): void
    {
        self::assertStringContainsString("route('jobs-monitor.schedule')", InstallCommand::menuCode());
        self::assertStringContainsString("route('jobs-monitor.queue')", InstallCommand::menuCode());
    }

    /**
     * Deleting a queued job removes it and redirects back with a success alert.
     */
    public function testDeletesQueuedJobAndFlashesSuccess(): void
    {
        $id = (int) DB::table('jobs')->insertGetId([
            'queue' => 'default',
            'payload' => '{}',
            'attempts' => 0,
            'available_at' => time(),
            'created_at' => time(),
        ]);

        $this->from(route('jobs-monitor.queue'))
            ->post(route('jobs-monitor.queue.delete', ['id' => $id]))
            ->assertRedirect(route('jobs-monitor.queue'))
            ->assertSessionHas('alert_messages.success');

        self::assertSame(0, DB::table('jobs')->count());
    }

    /**
     * An expected refusal of the core is shown as a warning, not as an error.
     */
    public function testShowsCoreRefusalAsWarning(): void
    {
        $this->from(route('jobs-monitor.queue'))
            ->post(route('jobs-monitor.queue.delete', ['id' => 999]))
            ->assertRedirect(route('jobs-monitor.queue'))
            ->assertSessionHas('alert_messages.warning');
    }

    /**
     * An invalid run mode is rejected by validation.
     */
    public function testValidatesRunMode(): void
    {
        $this->actingAs(new User(), backpack_guard_name());

        $this->from(route('jobs-monitor.schedule'))
            ->post(route('jobs-monitor.schedule.run', ['task' => md5('task')]), ['mode' => 'nope'])
            ->assertSessionHasErrors('mode');
    }
}
