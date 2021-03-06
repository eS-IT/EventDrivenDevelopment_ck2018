<?php
# 05_Contao4/ROOT/src/Vendor/Package/Classes/Listener/OnGreetingListenerTwo.php
namespace Vendor\Package\Classes\Listener;

use Vendor\Package\Classes\Events\OnGreetingEvent;

class OnGreetingListenerTwo
{
    public function generateMessage(OnGreetingEvent $greetingEvent)
    {
        $message    = $greetingEvent->getMessage();
        $message   .= " Contao 4 ist toll! ";
        $greetingEvent->setMessage($message);
    }
}
