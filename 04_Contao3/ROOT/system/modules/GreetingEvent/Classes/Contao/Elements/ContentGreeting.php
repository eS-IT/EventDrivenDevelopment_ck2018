<?php
# 04_Contao3/ROOT/system/modules/GreetingEvent/Classes/Contao/Elements/ContentGreeting.php
namespace Esit\GreetingEvent\Classes\Contao\Elements;

use Esit\GreetingEvent\Classes\Events\OnGreetingEvent;

class ContentGreeting extends \ContentElement
{
    protected $strTemplate = 'ce_greetings';

    protected function compile()
    {
        if (TL_MODE == 'BE') {
            $this->strTemplate        = 'be_wildcard';
            $this->Template           = new \BackendTemplate('be_wildcard');
            $this->Template->wildcard = "### ContentGreeting ###";
        } else {
            $event = new OnGreetingEvent();
            $event->setName($this->myname);
            \Esit\GreetingEvent\Classes\Helper\EventHelper::dispatch($event::EVENTNAME, $event);
            $this->Template->content = $event->getMessage();
        }
    }
}
