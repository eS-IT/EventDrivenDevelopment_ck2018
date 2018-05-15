<?php
# 03_SimpleEvent/ROOT/config/events_demo.php
/*
 * Diese Datei dient nur der Demonstration!
 * Im Projekt wird nur die Datei 03_SimpleEvent/ROOT/config/events.php verwendet!
 * (s. "Einfügen eines weiteren Listeners" weiter unten)
 */
$events['greeting.event'][1024] = ['Esit\Listener\OnGreetingListner', 'generateGreeting'];

return $events;
