<?php
# 03_SimpleEvent/ROOT/src/Esit/Listener/OnGreetingListner.php
namespace Esit\Listener;

class OnGreetingListner
{
    public function generateGreeting(\Esit\Events\OnGreetingEvent $greetingEvent)
    {
        $name       = $greetingEvent->getName();
        $message    = $greetingEvent->getMessage();
        $message   .= "Hallo $name!";
        $greetingEvent->setMessage($message);
    }
}
