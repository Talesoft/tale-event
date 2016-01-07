<?php

namespace Tale\Test;

use Tale\Event;

function event_test_function() {}

class EventTestClass
{
    public function testMethod() {}
    public static function staticTestMethod() {}
}

class EventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider invalidNameArgumentProvider
     */
    public function testInvalidConstructorArgs($arg)
    {

        $this->setExpectedException(\InvalidArgumentException::class);
        new Event($arg);

    }

    public function invalidNameArgumentProvider()
    {

        return [
            [1],
            [null],
            [false],
            [true],
            [1.1],
            [[]],
            [['value' => 1]],
            ['']
        ];
    }

    /**
     * @dataProvider validListenerProvider
     */
    public function testValidListeners($listener)
    {

        $event = new Event('test-event');
        $event->addListener($listener);

        $this->assertEquals(1, count($event->getListeners()));
    }

    public function validListenerProvider()
    {

        return [
            [function() {}],
            [[EventTestClass::class, 'staticTestMethod']],
            [[new EventTestClass(), 'testMethod']],
            [__NAMESPACE__.'\\event_test_function']
        ];
    }

    /**
     * @dataProvider invalidListenerProvider
     */
    public function testInvalidListeners($listener)
    {

        $this->setExpectedException(\InvalidArgumentException::class);
        $event = new Event('test-event');
        $event->addListener($listener);
    }

    public function invalidListenerProvider()
    {

        return [
            [1],
            [null],
            [false],
            [true],
            [1.1],
            [[]],
            [['value' => 1]],
            [''],
            ['some string']
        ];
    }

    public function testRemoveListener()
    {

        $event = new Event('test-event');

        $l1 = function() { return true; };
        $l2 = function() { return false; };
        $l3 = function() { return null; };

        $this->assertNotSame($l1, $l2);
        $this->assertNotSame($l2, $l3);

        $event->addListener($l1)
              ->addListener($l2)
              ->addListener($l3);

        $event->removeListener($l2);

        $this->assertSame([$l1, $l3], $event->getListeners());
    }

    public function testTriggering()
    {

        $event = new Event('test-event');

        $i = 5;
        while($i--) {

            $event->addListener(function ($e) {

                $e->value++;
            });
        }

        $args = new Event\Args(0);
        $event->trigger($args);
        $this->assertEquals(5, $args->value);
    }

    public function testCancellation()
    {

        $event = new Event('test-event');

        $i = 10;
        while($i--) {

            $event->addListener(function (Event\Args $e) use ($i) {

                $e->value++;

                if ($i === 3)
                    $e->cancel();
            });
        }

        $args = new Event\Args(0);
        $event->trigger($args);
        $this->assertEquals(7, $args->value);
    }

    public function testPrevention()
    {

        $event = new Event('test-event');
        $event->addListener(function(Event\Args $e) {

        });

        $this->assertEquals(true, $event->trigger());

        $event = new Event('test-event');
        $event->addListener(function(Event\Args $e) {

            return false;
        });

        $this->assertEquals(false, $event->trigger());

        $event = new Event('test-event');
        $event->addListener(function(Event\Args $e) {

            $e->preventDefault();
        });

        $this->assertEquals(false, $event->trigger());

        $event = new Event('test-event');
        $event->addListener(function(Event\Args $e) {

            $e->cancel();
        });

        $this->assertEquals(false, $event->trigger());
    }
}