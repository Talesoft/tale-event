<?php declare(strict_types=1);

namespace Tale\Test;

use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\ListenerProviderInterface;
use Tale\EventDispatcher;
use Tale\Test\StoppableTestEvent;
use Tale\Test\TestEvent;

/**
 * @coversDefaultClass \Tale\EventDispatcher
 */
class EventDispatcherTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::dispatch
     * @throws \ReflectionException
     */
    public function testDispatch(): void
    {
        $event = new TestEvent(0);
        $incrementor = function (TestEvent $event) {
            $event->setData($event->getData() + 1);
        };
        $provider = $this->getMockForAbstractClass(ListenerProviderInterface::class);
        $provider->expects($this->once())
            ->method('getListenersForEvent')
            ->with($event)
            ->willReturn([$incrementor, $incrementor, $incrementor]);
        $dispatcher = new EventDispatcher($provider);
        $result = $dispatcher->dispatch($event);

        self::assertSame($event, $result);
        self::assertSame(3, $result->getData());
    }

    /**
     * @covers ::__construct
     * @covers ::dispatch
     * @throws \ReflectionException
     */
    public function testDispatchNeverCallsListenerWhenPropagationStopped(): void
    {
        $event = new StoppableTestEvent(0, true);
        $incrementor = function (StoppableTestEvent $event) {
            $event->setData($event->getData() + 1);
        };
        $provider = $this->getMockForAbstractClass(ListenerProviderInterface::class);
        $provider->expects($this->once())
            ->method('getListenersForEvent')
            ->with($event)
            ->willReturn([$incrementor, $incrementor, $incrementor]);
        $dispatcher = new EventDispatcher($provider);
        $result = $dispatcher->dispatch($event);

        self::assertSame($event, $result);
        self::assertSame(0, $result->getData());
    }

    /**
     * @covers ::__construct
     * @covers ::dispatch
     * @throws \ReflectionException
     */
    public function testDispatchStopsWhenPropagationStopped(): void
    {
        $event = new StoppableTestEvent(0);
        $incrementor = function (StoppableTestEvent $event) {
            $event->setData($event->getData() + 1);
        };
        $provider = $this->getMockForAbstractClass(ListenerProviderInterface::class);
        $provider->expects($this->once())
            ->method('getListenersForEvent')
            ->with($event)
            ->willReturn([$incrementor, function (StoppableTestEvent $event) {
                $event->setData($event->getData() + 1);
                $event->stopPropagation();
            }, $incrementor]);
        $dispatcher = new EventDispatcher($provider);
        $result = $dispatcher->dispatch($event);

        self::assertSame($event, $result);
        self::assertSame(2, $result->getData());
    }
}
