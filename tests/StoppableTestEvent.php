<?php declare(strict_types=1);

namespace Tale\Test;

use Tale\Event\AbstractStoppableEvent;

class StoppableTestEvent extends AbstractStoppableEvent
{
    private $data;

    /**
     * AbstractTestEvent constructor.
     * @param $data
     * @param bool $propagationStopped
     */
    public function __construct($data = null, bool $propagationStopped = false)
    {
        parent::__construct($propagationStopped);
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }
}