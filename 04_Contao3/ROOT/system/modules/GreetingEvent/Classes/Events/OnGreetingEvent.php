<?php
# 04_Contao3/ROOT/system/modules/GreetingEvent/Classes/Events/OnGreetingEvent.php
namespace Esit\GreetingEvent\Classes\Events;

class OnGreetingEvent
{
    const EVENTNAME     = 'greeting.event';

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
