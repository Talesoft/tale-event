<?php declare(strict_types=1);

namespace Tale\Event;

abstract class AbstractStoppableEvent implements StoppableEventInterface
{
    /**
     * @var bool
     */
    private $propagationStopped;

    public function __construct(bool $propagationStopped = false)
    {
        $this->propagationStopped = $propagationStopped;
    }

    public function isPropagationStopped() : bool
    {
        return $this->propagationStopped;
    }

    public function stopPropagation(): void
    {
        $this->propagationStopped = true;
    }
}
