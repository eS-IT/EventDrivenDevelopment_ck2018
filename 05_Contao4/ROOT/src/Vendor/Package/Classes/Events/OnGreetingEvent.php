<?php
# 05_Contao4/ROOT/src/Vendor/Package/Classes/Events/OnGreetingEvent.php
namespace Vendor\Package\Classes\Events;

use Symfony\Component\EventDispatcher\Event;

class OnGreetingEvent extends Event
{
    const NAME          = 'greeting.event';

    protected $name     = '';

    protected $message  = '';

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }
}
