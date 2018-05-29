<?php
# 04_Contao3/ROOT/system/modules/GreetingEvent/Classes/Listener/OnGreetingListener.php
namespace Esit\GreetingEvent\Classes\Listener;

use Esit\GreetingEvent\Classes\Events\OnGreetingEvent;

class OnGreetingListener
{
    public function generateGreeting(OnGreetingEvent $greetingEvent)
    {
        $name       = $greetingEvent->getName();
        $message    = $greetingEvent->getMessage();
        $message   .= "Hallo $name!";
        $greetingEvent->setMessage($message);
    }
}
