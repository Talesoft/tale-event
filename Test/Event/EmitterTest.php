<?php

namespace Tale\Test\Event;

use Tale\Event;

class Socket
{

    use Event\EmitterTrait;

    public function connect()
    {

        if ($this->emit('connecting')) {

            $this->emit('connected');
        }
    }
}

class EmitterTest extends \PHPUnit_Framework_TestCase
{

    public function testBinding()
    {

        $socket = new Socket();
        $socket->on('event-1', function() {});
        $socket->on('event-2', function() {});
        $socket->on('event-1', function() {});

        $this->assertEquals(2, count($socket->getEvent('event-1')->getListeners()));
        $this->assertEquals(1, count($socket->getEvent('event-2')->getListeners()));
    }

    public function testUnbinding()
    {

        $socket = new Socket();
        $socket->on('event-1', function() {});
        $socket->on('event-1', function() {});
        $socket->on('event-1', $func = function() {});
        $socket->on('event-1', function() {});

        $socket->off('event-1', $func);

        $this->assertEquals(3, count($socket->getEvent('event-1')->getListeners()));
    }

    public function testEmit()
    {

        $socket = new Socket();
        $i = 0;
        $socket->on('connecting', function($e, $event) use (&$i) {

            $this->assertEquals($event->getName(), 'connecting');
            $i++;
        });

        $socket->on('connected', function($e, $event) use (&$i) {

            $this->assertEquals($event->getName(), 'connected');
            $i++;
        });

        $socket->connect();
        $this->assertEquals(2, $i);
    }

    public function testPrevention()
    {

        $socket = new Socket();
        $i = 0;
        $socket->on('connecting', function(Event\Args $e, $event) use (&$i) {

            $this->assertEquals($event->getName(), 'connecting');
            $e->preventDefault();
            $i++;
        });

        $socket->on('connected', function($e, $event) use (&$i) {

            $i++;
        });

        $socket->connect();
        $this->assertEquals(1, $i);

        $socket = new Socket();
        $i = 0;
        $socket->on('connecting', function(Event\Args $e, $event) use (&$i) {

            $this->assertEquals($event->getName(), 'connecting');
            $i++;

            return false;
        });

        $socket->on('connected', function($e, $event) use (&$i) {

            $i++;
        });

        $socket->connect();
        $this->assertEquals(1, $i);

        $socket = new Socket();
        $i = 0;
        $socket->on('connecting', function(Event\Args $e, $event) use (&$i) {

            $this->assertEquals($event->getName(), 'connecting');
            $e->cancel();
            $i++;
        });

        $socket->on('connected', function($e, $event) use (&$i) {

            $i++;
        });

        $socket->connect();
        $this->assertEquals(1, $i);
    }
}