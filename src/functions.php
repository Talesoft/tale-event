<?php declare(strict_types=1);

namespace Tale;

use Psr\EventDispatcher\ListenerProviderInterface;
use Tale\Event\ListenerProvider\ChainListenerProvider;
use Tale\Event\ListenerProvider\ClassMapListenerProvider;

function event_dispatcher(ListenerProviderInterface $listenerProvider = null): EventDispatcher
{
    return new EventDispatcher($listenerProvider ?? event_listener_provider_reflection());
}

function event_listener_provider_reflection(array $listeners = []): ClassMapListenerProvider
{
    return new ClassMapListenerProvider($listeners);
}

function event_listener_provider_class_map(array $classMap): ClassMapListenerProvider
{
    return new ClassMapListenerProvider($classMap);
}

function event_listener_provider_chain(iterable $providers): ChainListenerProvider
{
    return new ChainListenerProvider($providers);
}
