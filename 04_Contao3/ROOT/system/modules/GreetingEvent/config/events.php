<?php
/**
 * @package     ck2018
 * @filesource  events.php
 * @version     1.0.0
 * @since       24.04.18 - 14:58
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     EULA
 */


$events['OnGreetingEvent'][1024] = ['Esit\GreetingEvent\Classes\Listener\OnGreetingListener', 'generateGreeting'];
$events['OnGreetingEvent'][2048] = ['Esit\GreetingEvent\Classes\Listener\OnGreetingListener', 'generateMessage'];

return $events;