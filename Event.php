<?php

namespace Tale;

class Event
{

    private $name;
    private $listeners;

    public function __construct($name)
    {

        if (!is_string($name) || strlen($name) < 1)
            throw new \InvalidArgumentException(
                'Arguments 1 passed to Event->__constructs needs to be a string with at least 1 character'
            );

        $this->name = $name;
        $this->listeners = [];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getListeners()
    {
        return $this->listeners;
    }

    public function addListener($listener)
    {

        if (!is_callable($listener))
            throw new \InvalidArgumentException(
                'Argument 1 passed to Event->addListener needs to be a valid callback'
            );

        $this->listeners[] = $listener;

        return $this;
    }

    public function removeListener($listener)
    {

        if (!is_callable($listener))
            throw new \InvalidArgumentException(
                'Argument 1 passed to Event->removeListener needs to be a valid callback'
            );

        $index = array_search($listener, $this->listeners, true);

        if ($index === false)
            throw new \InvalidArgumentException(
                'Argument 1 passed to Event->removeListener needs to be a registered callback'
            );

        array_splice($this->listeners, $index, 1);

        return $this;
    }

    public function trigger($args = null)
    {

        $args = $args instanceof Event\Args ? $args : new Event\Args($args);

        $len = count($this->listeners);

        if ($len < 1)
            return true;

        for ($i = 0; $i < $len; $i++) {

            if (call_user_func($this->listeners[$i], $args, $this) === false)
                $args->preventDefault();

            if ($args->isCancelled())
                break;
        }

        return !($args->isDefaultPrevented() || $args->isCancelled());
    }
}