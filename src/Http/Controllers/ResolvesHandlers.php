<?php

declare(strict_types=1);

namespace MikhailMelnikov\BackpackJobsMonitor\Http\Controllers;

/**
 * Creates the core use case handlers through the service container.
 *
 * A handler can therefore be replaced in the application, e.g. in AppServiceProvider:
 * `$this->app->bind(RetryFailedJobsHandler::class, MyRetryFailedJobsHandler::class)`.
 * The constructor arguments are passed by name, so a replacement keeps the parameter
 * names of the original constructor (or receives them as the second argument of a
 * closure binding).
 */
trait ResolvesHandlers
{
    /**
     * Resolve a use case handler from the container.
     *
     * @template T of object
     *
     * @param class-string<T>      $class      Handler class of the core
     * @param array<string, mixed> $parameters Constructor arguments keyed by parameter name
     *
     * @return T
     */
    protected function handler(string $class, array $parameters = []): object
    {
        return app()->make($class, $parameters);
    }
}
