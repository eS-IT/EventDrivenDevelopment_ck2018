<?php
# 03_SimpleEvent/ROOT/src/Events/OnGreetingEvent.php
namespace Esit\Events;

class OnGreetingEvent
{
    /**
     * Names des Events
     */
    const EVENTNAME = 'greeting.event';

    /**
     * Name der Person, die gegrüßt werden soll.
     * @var string
     */
    protected $name = '';

    /**
     * Fertige Grußbotschaft
     * @var array
     */
    protected $message = '';

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
