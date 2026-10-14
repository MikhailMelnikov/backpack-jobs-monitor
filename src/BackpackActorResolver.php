<?php

declare(strict_types=1);

namespace MikhailMelnikov\BackpackJobsMonitor;

use MikhailMelnikov\JobsMonitor\Contracts\ActorResolver;

/**
 * Resolves the Backpack admin who performs a manual action.
 */
final class BackpackActorResolver implements ActorResolver
{
    /**
     * Identifier of the user authenticated in the Backpack guard.
     */
    public function id(): int|string|null
    {
        /** @var int|string|null $id */
        $id = backpack_auth()->id();

        return $id;
    }
}
