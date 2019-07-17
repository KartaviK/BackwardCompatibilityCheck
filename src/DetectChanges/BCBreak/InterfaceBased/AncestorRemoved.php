<?php

declare(strict_types=1);

namespace Roave\BackwardCompatibility\DetectChanges\BCBreak\InterfaceBased;

use Roave\BackwardCompatibility\Change;
use Roave\BackwardCompatibility\Changes;
use Roave\BetterReflection\Reflection\ReflectionClass;
use function array_diff;
use function array_merge;
use function Safe\json_encode;
use function Safe\sprintf;

/**
 * An interface ancestor cannot be removed, as that breaks type checking in consumers.
 */
final class AncestorRemoved implements InterfaceBased
{
    public function __invoke(ReflectionClass $fromInterface, ReflectionClass $toInterface) : Changes
    {
        $removedAncestors = array_merge(
            array_diff($fromInterface->getInterfaceNames(), $toInterface->getInterfaceNames())
        );

        if (! $removedAncestors) {
            return Changes::empty();
        }

        return Changes::fromList(Change::removed(
            sprintf(
                'These ancestors of %s have been removed: %s',
                $fromInterface->getName(),
                json_encode($removedAncestors)
            ),
            true
        ));
    }
}
