<?php declare(strict_types=1);

namespace Tale\Event;

use Closure;

interface ListenerRegistryInterface
{
    public function addListener(Closure $listener): void;
    public function removeListener(Closure $listener): void;
}