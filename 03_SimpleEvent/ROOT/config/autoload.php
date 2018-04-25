<?php
/**
 * @author      pfroch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     EULA
 * @package     ck2018
 * @filesource  autoload.php
 * @version     1.0.0
 * @since       25.04.18 - 15:13
 */
include_once 'vendor/autoload.php';
include_once 'config/events.php';
include_once 'src/Helper/EventHelper.php';
include_once 'src/Content/EventCaller.php';
include_once 'src/Events/OnGreetingEvent.php';
include_once 'src/Listener/OnGreetingListner.php';
include_once 'src/Listener/OnGreetingListnerTwo.php';
