<?php declare(strict_types=1);

namespace Tale\Test\Event\ListenerProvider;

use PHPUnit\Framework\TestCase;
use Tale\Event\ListenerProvider\ReflectionListenerProvider;
use Tale\Event\StoppableEventInterface;
use Tale\Test\AbstractTestEvent;
use Tale\Test\FinalTestEvent;
use Tale\Test\StoppableTestEvent;
use Tale\Test\TestEvent;
use Tale\Test\TestEventInterface;

/**
 * @coversDefaultClass \Tale\Event\ListenerProvider\ReflectionListenerProvider
 */
class ReflectionListenerProviderTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getListenersForEvent
     */
    public function testConstruct(): void
    {
        $listener = function (TestEvent $event) {};
        $provider = new ReflectionListenerProvider([
            $listener
        ]);

        self::assertEquals([$listener], iterator_to_array($provider->getListenersForEvent(new TestEvent())));
    }

    /**
     * @covers ::addListener
     * @covers ::getListenersForEvent
     */
    public function testAddListener(): void
    {
        $listener = function (TestEvent $event) {};
        $provider = new ReflectionListenerProvider();
        $provider->addListener($listener);

        self::assertEquals([$listener], iterator_to_array($provider->getListenersForEvent(new TestEvent())));
    }

    /**
     * @covers ::addListener
     * @covers ::getListenersForEvent
     */
    public function testRemoveListener(): void
    {
        $listener = function (TestEvent $event) {};
        $provider = new ReflectionListenerProvider();
        $provider->addListener($listener);
        self::assertEquals([$listener], iterator_to_array($provider->getListenersForEvent(new TestEvent())));
        $provider->removeListener($listener);
        self::assertEquals([], iterator_to_array($provider->getListenersForEvent(new TestEvent())));
    }

    /**
     * @covers ::addListener
     * @covers ::getListenersForEvent
     */
    public function testGetListenersForEvent(): void
    {
        $listeners = [
            $listener = function (TestEvent $event) {},
            $listenerAbstract = function (AbstractTestEvent $event) {},
            function (\stdClass $event) {},
            $listenerFinal = function (FinalTestEvent $event) {},
            $listenerStoppable = function (StoppableEventInterface $event) {},
            $listenerInterface = function (TestEventInterface $event) {}
        ];
        $provider = new ReflectionListenerProvider($listeners);

        self::assertEquals([
            $listener,
            $listenerAbstract,
            $listenerFinal,
            $listenerInterface
        ], iterator_to_array($provider->getListenersForEvent(new FinalTestEvent())));

        self::assertEquals([
            $listenerStoppable
        ], iterator_to_array($provider->getListenersForEvent(new StoppableTestEvent())));
    }
}
