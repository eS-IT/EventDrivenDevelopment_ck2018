<?php
/**
 * @package     ck2018
 * @filesource  autoload.php
 * @version     1.0.0
 * @since       24.04.18 - 14:40
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     EULA
 */

/**
 * Variables
 */
$strFolder      = 'GreetingEvent';
$strNamespace   = 'Esit\\' . $strFolder;

/**
 * Register the namespaces
 */
 ClassLoader::addNamespaces(array
 (
    $strNamespace
 ));

 /**
  * Register the classes
  */
 ClassLoader::addClasses(array
 (
    // Events
    $strNamespace . '\Classes\Contao\Elements\ContentGreeting'  => "system/modules/$strFolder/Classes/Contao/Elements/ContentGreeting.php",
    $strNamespace . '\Classes\Events\OnGreetingEvent'           => "system/modules/$strFolder/Classes/Events/OnGreetingEvent.php",
    $strNamespace . '\Classes\Helper\EventHelper'               => "system/modules/$strFolder/Classes/Helper/EventHelper.php",
    $strNamespace . '\Classes\Listener\OnGreetingListener'      => "system/modules/$strFolder/Classes/Listener/OnGreetingListener.php",
    $strNamespace . '\Classes\Listener\OnGreetingListenerTwo'   => "system/modules/$strFolder/Classes/Listener/OnGreetingListenerTwo.php",

    // Callbacks
    $strNamespace . '\Classes\Contao\Hooks\MyHook'              => "system/modules/$strFolder/Classes/Contao/Hooks/MyHook.php",

    // Hooks
    $strNamespace . '\Classes\Contao\Callbacks\MyCallback'      => "system/modules/$strFolder/Classes/Contao/Callbacks/MyCallback.php"
 ));

 /**
  * Register the templates
  */
TemplateLoader::addFiles(array
(
    'ce_greetings' => "system/modules/$strFolder/templates",
));