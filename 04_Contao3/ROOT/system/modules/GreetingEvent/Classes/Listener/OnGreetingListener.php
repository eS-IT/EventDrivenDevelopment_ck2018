<?php
/**
 * @package     ck2018
 * @filesource  OnGreetingListener.php
 * @version     1.0.0
 * @since       24.04.18 - 14:48
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     EULA
 */
namespace Esit\GreetingEvent\Classes\Listener;

use Esit\GreetingEvent\Classes\Events\OnGreetingEvent;

/**
 * Class OnGreetingListener
 * @package Esit\GreetingEvent\Classes\Listener
 */
class OnGreetingListener
{


    /**
     * Erzeugt die Grußbotschaft
     * @param OnGreetingEvent $greetingEvent
     */
    public function generateGreeting(OnGreetingEvent $greetingEvent)
    {
        $name       = $greetingEvent->getName();
        $message    = "Hallo $name!";
        $greetingEvent->setMessage($message);
    }


    /**
     * Erzeugt die Grußbotschaft
     * @param OnGreetingEvent $greetingEvent
     */
    public function generateMessage(OnGreetingEvent $greetingEvent)
    {
        $message    = $greetingEvent->getMessage();
        $message   .= " Contao 3 ist toll!";
        $greetingEvent->setMessage($message);
    }
}
