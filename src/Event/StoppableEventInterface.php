<?php declare(strict_types=1);

namespace Tale\Event;

interface StoppableEventInterface extends \Psr\EventDispatcher\StoppableEventInterface
{
    public function stopPropagation(): void;
}
