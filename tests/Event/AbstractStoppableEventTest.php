<?php declare(strict_types=1);

namespace Tale\Test\Stream;

use PHPUnit\Framework\TestCase;
use Tale\Test\StoppableTestEvent;

/**
 * @coversDefaultClass \Tale\Event\AbstractStoppableEvent
 */
class AbstractStoppableEventTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::stopPropagation
     * @covers ::isPropagationStopped
     */
    public function testStopPropagation(): void
    {
        $event = new StoppableTestEvent(null, true);
        self::assertTrue($event->isPropagationStopped());
        $event = new StoppableTestEvent();
        self::assertFalse($event->isPropagationStopped());
        $event->stopPropagation();
        self::assertTrue($event->isPropagationStopped());
    }
}
