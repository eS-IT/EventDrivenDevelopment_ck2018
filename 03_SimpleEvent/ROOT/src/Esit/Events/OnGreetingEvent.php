<?php
# 03_SimpleEvent/ROOT/src/Esit/Events/OnGreetingEvent.php
namespace Esit\Events;

class OnGreetingEvent
{
    /**
     * Name des Events
     */
    const NAME = 'greeting.event';

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
