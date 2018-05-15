<?php
# 05_Contao4/ROOT/src/Vendor/Package/Classes/Listener/OnGreetingListener.php
namespace Vendor\Package\Classes\Listener;

use Vendor\Package\Classes\Events\OnGreetingEvent;

class OnGreetingListener
{
    public function generateGreeting(OnGreetingEvent $greetingEvent)
    {
        $name       = $greetingEvent->getName();
        $message    = "Hallo $name!";
        $greetingEvent->setMessage($message);
    }
}
