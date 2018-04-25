<?php
/**
 * @package     ck2018
 * @filesource  EventHelper.php
 * @version     1.0.0
 * @since       24.04.18 - 14:51
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     EULA
 */
namespace Esit\GreetingEvent\Classes\Helper;

/**
 * Class EventHelper
 * @package Esit\GreetingEvent\Classes\Helper
 */
class EventHelper
{


    /**
     * @param $eventName
     * @param $event
     */
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
