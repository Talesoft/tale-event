<?php declare(strict_types=1);

namespace Tale\Event\ListenerProvider;

use Psr\EventDispatcher\ListenerProviderInterface;

final class ClassMapListenerProvider implements ListenerProviderInterface
{
    /**
     * @var callable[][]
     */
    private $classMap;

    public function __construct(array $classMap)
    {
        $this->classMap = $classMap;
    }

    public function getListenersForEvent(object $event): iterable
    {
        $className = get_class($event);
        if (!isset($this->classMap[$className])) {
            return;
        }
        yield from $this->classMap[$className];
    }
}
