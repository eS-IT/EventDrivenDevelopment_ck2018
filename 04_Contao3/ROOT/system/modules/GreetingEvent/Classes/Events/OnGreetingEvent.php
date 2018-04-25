<?php
/**
 * @package     ck2018
 * @filesource  OnGreetingEvent.php
 * @version     1.0.0
 * @since       24.04.18 - 14:46
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     EULA
 */
namespace Esit\GreetingEvent\Classes\Events;

/**
 * Class OnGreetingEvent
 * @package Esit\GreetingEvent\Classes\Events
 */
class OnGreetingEvent
{

    /**
     * Names des Events
     */
    const EVENTNAME = 'OnGreetingEvent';

    /**
     * Name der Person, die gegrüßt werden soll.
     * @var string
     */
    protected $name = '';

    /**
     * Fertige Grußbotschaft
     * @var array
     */
    protected $message = '';


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @return array
     */
    public function getMessage()
    {
        return $this->message;
    }


    /**
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
