
[![Packagist](https://img.shields.io/packagist/v/talesoft/tale-event.svg?style=for-the-badge)](https://packagist.org/packages/talesoft/tale-event)
[![License](https://img.shields.io/github/license/Talesoft/tale-event.svg?style=for-the-badge)](https://github.com/Talesoft/tale-event/blob/master/LICENSE.md)
[![CI](https://img.shields.io/travis/Talesoft/tale-event.svg?style=for-the-badge)](https://travis-ci.org/Talesoft/tale-event)
[![Coverage](https://img.shields.io/codeclimate/coverage/Talesoft/tale-stream.svg?style=for-the-badge)](https://codeclimate.com/github/Talesoft/tale-event)

Tale Event
==========

What is Tale Event?
-------------------

A PSR-14 Event Dispatcher implementation

Installation
------------

```bash
composer req talesoft/tale-event
```

Usage
-----

```php
use Tale\Event\ListenerProvider\ReflectionListenerProvider;
use Tale\EventDispatcher;

$provider = new ReflectionListenerProvider();
$dispatcher = new EventDispatcher($provider);

class MyEvent
{
    private $message = '';
    
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
    
    public function getMessage(): string
    {
        return $this->message;
    }
}

$provider->addListener(function (MyEvent $event) {
    $event->setMessage('Hello from listener!');
});

$event = new MyEvent();
$dispatcher->dispatch($event);
echo $event->getMessage(); // "Hello from listener!"
```
