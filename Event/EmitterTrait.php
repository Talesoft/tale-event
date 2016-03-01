<?php

namespace Tale\Event;

use Tale\Event;

trait EmitterTrait
{

    /**
     * @var Event[]
     */
    private $events = [];

    /**
     * @param string $name
     *
     * @return Event
     */
    public function getEvent($name)
    {

        if (!isset($this->events[$name]))
            $this->events[$name] = new Event($name);

        return $this->events[$name];
    }

    public function on($eventName, $listener)
    {

        $event = $this->getEvent($eventName);
        $event->addListener($listener);

        return $this;
    }

    public function once($eventName, $listener)
    {

        return $this->on($eventName, function ($e) use ($eventName, $listener) {

            $this->off($eventName, $listener);
            return call_user_func($listener, $e);
        });
    }

    public function off($eventName, $listener)
    {

        $event = $this->getEvent($eventName);
        $event->removeListener($listener);

        return $this;
    }

    public function emit($eventName, $args = null)
    {

        if (!isset($this->events[$eventName]))
            return true;

        return $this->events[$eventName]->trigger($args);
    }
}