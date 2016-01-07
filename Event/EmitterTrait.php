<?php

namespace Tale\Event;

use Tale\Event;

trait EmitterTrait
{

    /**
     * @var Event[]
     */
    private $_events = [];

    /**
     * @param string $name
     *
     * @return Event
     */
    public function getEvent($name)
    {

        if (!isset($this->_events[$name]))
            $this->_events[$name] = new Event($name);

        return $this->_events[$name];
    }

    public function on($eventName, $listener)
    {

        $event = $this->getEvent($eventName);
        $event->addListener($listener);

        return $this;
    }

    public function off($eventName, $listener)
    {

        $event = $this->getEvent($eventName);
        $event->removeListener($listener);

        return $this;
    }

    public function emit($eventName, $args = null)
    {

        if (!isset($this->_events[$eventName]))
            return true;

        return $this->_events[$eventName]->trigger($args);
    }
}