<?php
# 03_SimpleEvent/ROOT/src/Helper/EventHelper.php
namespace Esit\Helper;

class EventHelper
{
    public static function dispatch($eventName, $event)
    {
        $di             = new \Dice\Dice();
        $allListener    = include __DIR__ . '/../../../config/events.php';

        if (is_array($allListener) && array_key_exists($eventName, $allListener)) {
            ksort($allListener[$eventName]);

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
