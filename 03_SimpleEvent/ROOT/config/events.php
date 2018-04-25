<?php
# ROOT/config/events.php
$events['OnGreetingEvent'][1024] = ['Esit\Listener\OnGreetingListner', 'generateGreeting'];
$events['OnGreetingEvent'][2048] = ['Esit\Listener\OnGreetingListnerTwo', 'modifyGreeting']; # new!

return $events;