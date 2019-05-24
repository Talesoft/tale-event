<?php declare(strict_types=1);

namespace Tale\Test;

abstract class AbstractTestEvent implements TestEventInterface
{
    private $data;

    /**
     * AbstractTestEvent constructor.
     * @param $data
     */
    public function __construct($data = null)
    {
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
