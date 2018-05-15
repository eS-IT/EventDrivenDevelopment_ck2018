<?php
# 04_Contao3/ROOT/system/modules/GreetingEvent/Classes/Helper/EventHelper.php
namespace Esit\GreetingEvent\Classes\Helper;

class EventHelper
{
    public static function dispatch($eventName, $event)
    {
        $di             = new \Dice\Dice();
        $allListener    = include __DIR__ . '/../../config/events.php';

        if (is_array($allListener) && array_key_exists($eventName, $allListener)) {
            foreach ($allListener[$eventName] as $listner) {
                if (count($listner) == 2) {
                    $class   = $di->create($listner[0]);
                    $methode = $listner[1];

                    if ($class && method_exists($class, $methode)) {
                        $class->$methode($event);
                    }
                }
            }
        }
    }

}
