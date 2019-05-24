<?php declare(strict_types=1);

namespace Tale\Test;

interface TestEventInterface
{
    public function setData($data);
    public function getData();
}