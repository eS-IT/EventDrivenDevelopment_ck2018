<?php
# 04_Contao3/ROOT/system/modules/GreetingEvent/Classes/Listener/OnGreetingListenerTwo.php
namespace Esit\GreetingEvent\Classes\Listener;

use Esit\GreetingEvent\Classes\Events\OnGreetingEvent;

class OnGreetingListenerTwo
{
    public function generateMessage(OnGreetingEvent $greetingEvent)
    {
        $message    = $greetingEvent->getMessage();
        $message   .= " Contao 3 ist toll!";
        $greetingEvent->setMessage($message);
    }
}
