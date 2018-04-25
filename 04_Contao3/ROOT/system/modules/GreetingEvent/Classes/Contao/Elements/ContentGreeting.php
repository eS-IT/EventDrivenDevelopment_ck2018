<?php
/**
 * @package     ck2018
 * @filesource  ContentGreeting.php
 * @version     1.0.0
 * @since       24.04.18 - 14:39
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     EULA
 */
namespace Esit\GreetingEvent\Classes\Contao\Elements;

use Esit\GreetingEvent\Classes\Events\OnGreetingEvent;

/**
 * Class ContentGreeting
 * @package GreetingEvent\Classes\Contao\Elements
 */
class ContentGreeting extends \ContentElement
{


    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'ce_greetings';


    /**
     * Generate the content element
     */
    protected function compile()
    {
        if (TL_MODE == 'BE') {
            $this->genBeOutput();
        } else {
            $this->genFeOutput();
        }
    }


    /**
     * Erzeugt die Ausgabe für das Backend.
     */
    protected function genBeOutput()
    {
        $this->strTemplate        = 'be_wildcard';
        $this->Template           = new \BackendTemplate($this->strTemplate);
        $this->Template->title    = $this->headline;
        $this->Template->wildcard = "### ContentGreeting ###";
    }


    /**
     * Erzeugt die Ausgabe für das Frontend.
     */
    protected function genFeOutput()
    {
        $event = new OnGreetingEvent();
        $event->setName($this->myname);
        \Esit\GreetingEvent\Classes\Helper\EventHelper::dispatch($event::EVENTNAME, $event);
        $this->Template->content = $event->getMessage();
    }
}
