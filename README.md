
# Tale Event

A minimalistic event library for PHP

---


## Usage

### Single Events

```php
$event = new Event('event-name');

$event->addListener(function($e) {
    
    echo "Hello from $e->libraryName!";
});

$event->trigger([
    'libraryName' => 'Tale Event'
]);
```


### Default Prevention

```php
$event->addListener(function($e) {

    echo "I do my own stuff!";
    $e->preventDefault();
});

if (!$event->trigger()) {
    
    //This will never be called, since ->trigger() returns false if the default was prevented with $e->preventDefault()
    echo "Do the default stuff...";
}
```


### Cancellation

```php
$event->addListener(function($e) {

    echo "I will be called";
    $e->cancel();
});

$event->addListener(function($e) {

    echo "I won't be called, since the previous event cancelled me";
});

$event->trigger(); //<- Returns `false` since the event was cancelled, so we cancel the default as well
```


### Event Emitters

```php

class Socket
{
    use Tale\Event\EmitterTrait;
    
    public function connect()
    {
        
        if ($this->emit('connecting')) {
            
            //connect...
            $this->emit('connected');
        }
    }
}

$socket = new Socket();

$socket->on('connecting', function($e) {
    
    if ($someInvalidState)
        $e->preventDefault(); //Prevent connecting
});

$socket->on('connected', function() {
    
    echo 'Connected!';
});
```