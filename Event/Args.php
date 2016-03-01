<?php

namespace Tale\Event;

class Args
{

    private $data;
    private $cancelled;
    private $defaultPrevented;

    public function __construct($data = null)
    {

        $this->data = [];
        $this->cancelled = false;
        $this->defaultPrevented = false;

        $this->setData($data);
    }

    public function getData()
    {

        return $this->data;
    }

    public function setData($data)
    {

        if ($data === null) {

            $this->data = [];
            return $this;
        }

        if (!is_array($data))
            $data = ['value' => $data];

        $this->data = $data;

        return $this;
    }

    public function isCancelled()
    {

        return $this->cancelled;
    }

    public function cancel()
    {

        $this->cancelled = true;

        return $this;
    }

    public function isDefaultPrevented()
    {

        return $this->defaultPrevented;
    }

    public function preventDefault()
    {

        $this->defaultPrevented = true;

        return $this;
    }

    public function &__get($key)
    {

        return $this->data[$key];
    }

    public function __set($key, $value)
    {

        $this->data[$key] = $value;
    }
}