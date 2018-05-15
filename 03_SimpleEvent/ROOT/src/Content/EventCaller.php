<?php
# 03_SimpleEvent/ROOT/src/Content/EventCaller.php
namespace Esit\Content;

class EventCaller
{
    public static function callEvent()
    {
        $event      = new \Esit\Events\OnGreetingEvent();
        $event->setName('Leo');
        \Esit\Helper\EventHelper::dispatch($event::EVENTNAME, $event);
        echo $event->getMessage();  # Print: Hallo Leo!
    }
}
