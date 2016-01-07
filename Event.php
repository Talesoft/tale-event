<?php

namespace Tale;

class Event
{

    private $_name;
    private $_listeners;

    public function __construct($name)
    {

        if (!is_string($name) || strlen($name) < 1)
            throw new \InvalidArgumentException(
                'Arguments 1 passed to Event->__constructs needs to be a string with at least 1 character'
            );

        $this->_name = $name;
        $this->_listeners = [];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return array
     */
    public function getListeners()
    {
        return $this->_listeners;
    }

    public function addListener($listener)
    {

        if (!is_callable($listener))
            throw new \InvalidArgumentException(
                'Argument 1 passed to Event->addListener needs to be a valid callback'
            );

        $this->_listeners[] = $listener;

        return $this;
    }

    public function removeListener($listener)
    {

        if (!is_callable($listener))
            throw new \InvalidArgumentException(
                'Argument 1 passed to Event->removeListener needs to be a valid callback'
            );

        $index = array_search($listener, $this->_listeners, true);

        if ($index === false)
            throw new \InvalidArgumentException(
                'Argument 1 passed to Event->removeListener needs to be a registered callback'
            );

        array_splice($this->_listeners, $index, 1);

        return $this;
    }

    public function trigger($args = null)
    {

        $args = $args instanceof Event\Args ? $args : new Event\Args($args);

        $len = count($this->_listeners);

        if ($len < 1)
            return true;

        for ($i = 0; $i < $len; $i++) {

            if (call_user_func($this->_listeners[$i], $args, $this) === false)
                $args->preventDefault();

            if ($args->isCancelled())
                break;
        }

        return !($args->isDefaultPrevented() || $args->isCancelled());
    }
}