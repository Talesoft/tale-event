<?php

namespace Tale\Event;

class Args
{

    private $_data;
    private $_cancelled;
    private $_defaultPrevented;

    public function __construct($data = null)
    {

        $this->_data = [];
        $this->_cancelled = false;
        $this->_defaultPrevented = false;

        $this->setData($data);
    }

    public function getData()
    {

        return $this->_data;
    }

    public function setData($data)
    {

        if ($data === null) {

            $this->_data = [];
            return $this;
        }

        if (!is_array($data))
            $data = ['value' => $data];

        $this->_data = $data;

        return $this;
    }

    public function isCancelled()
    {

        return $this->_cancelled;
    }

    public function cancel()
    {

        $this->_cancelled = true;

        return $this;
    }

    public function isDefaultPrevented()
    {

        return $this->_defaultPrevented;
    }

    public function preventDefault()
    {

        $this->_defaultPrevented = true;

        return $this;
    }

    public function &__get($key)
    {

        return $this->_data[$key];
    }

    public function __set($key, $value)
    {

        $this->_data[$key] = $value;
    }
}