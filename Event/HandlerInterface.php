<?php

namespace Tale\Event;

use Tale\Event;

interface HandlerInterface
{

    public function __invoke(Args $e, Event $event);
}