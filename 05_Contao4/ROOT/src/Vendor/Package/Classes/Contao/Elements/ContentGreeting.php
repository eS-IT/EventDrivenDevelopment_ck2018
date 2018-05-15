<?php
# 05_Contao4/ROOT/src/Vendor/Package/Classes/Contao/Elements/ContentGreeting.php
namespace Vendor\Package\Classes\Contao\Elements;

use Vendor\Package\Classes\Events\OnGreetingEvent;

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
            $dispatcher = \System::getContainer()->get('event_dispatcher'); // ACHTUNG: schon wieder deprecated!
            $event      = new OnGreetingEvent();
            $event->setName($this->myname);
            $dispatcher->dispatch($event::NAME, $event);
            $this->Template->content = $event->getMessage();
        }
    }
}
