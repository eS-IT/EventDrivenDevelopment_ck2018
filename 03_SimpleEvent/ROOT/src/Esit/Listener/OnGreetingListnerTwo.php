<?php
# 03_SimpleEvent/ROOT/src/Esit/Listener/OnGreetingListnerTwo.php
namespace Esit\Listener;

class OnGreetingListnerTwo
{
    public function modifyGreeting(\Esit\Events\OnGreetingEvent $greetingEvent)
    {
        $message = $greetingEvent->getMessage();
        $message.= ' Contao ist toll! ';
        $greetingEvent->setMessage($message);
    }
}
