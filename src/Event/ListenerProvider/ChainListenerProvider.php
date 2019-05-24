<?php declare(strict_types=1);

namespace Tale\Event\ListenerProvider;

use Psr\EventDispatcher\ListenerProviderInterface;

final class ChainListenerProvider implements ListenerProviderInterface
{
    /**
     * @var iterable<ListenerProviderInterface>
     */
    private $providers;

    /**
     * ChainListenerProvider constructor.
     * @param iterable<ListenerProviderInterface> $providers
     */
    public function __construct(iterable $providers)
    {
        $this->providers = $providers;
    }

    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->providers as $provider) {
            /** @var ListenerProviderInterface $provider */
            yield from $provider->getListenersForEvent($event);
        }
    }
}
