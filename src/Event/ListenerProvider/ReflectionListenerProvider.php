<?php declare(strict_types=1);

namespace Tale\Event\ListenerProvider;

use Closure;
use Psr\EventDispatcher\ListenerProviderInterface;
use SplObjectStorage;
use Tale\Event\InvalidListenerException;
use Tale\Event\ListenerRegistryInterface;

final class ReflectionListenerProvider implements ListenerProviderInterface, ListenerRegistryInterface
{
    /**
     * @var SplObjectStorage
     */
    private $listeners;

    public function __construct(iterable $listeners = [])
    {
        $this->listeners = new SplObjectStorage();
        foreach ($listeners as $listener) {
            $this->addListener($listener);
        }
    }

    public function addListener(Closure $handler): void
    {
        try {
            $refl = new \ReflectionFunction($handler);
        } catch (\Exception $ex) {
            throw new InvalidListenerException('Failed to reflect closure', $ex);
        }
        $parameters = $refl->getParameters();
        if (count($parameters) !== 1) {
            throw new InvalidListenerException('Closure needs to have exactly one parameter');
        }

        $class = $parameters[0]->getClass();
        if (!$class) {
            throw new InvalidListenerException('Closure parameter needs a type-hint on a valid class');
        }


        $this->listeners->attach($handler, $class->getName());
    }

    public function removeListener(Closure $listener): void
    {
        $this->listeners->detach($listener);
    }

    public function getListenersForEvent(object $event): iterable
    {
        $className = get_class($event);
        foreach ($this->listeners as $listener) {
            /** @var \ReflectionClass $class */
            $listenerClassName = $this->listeners->offsetGet($listener);
            if (is_a($className, $listenerClassName, true)) {
                yield $listener;
            }
        }
    }
}
