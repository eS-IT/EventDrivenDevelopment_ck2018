<?php
# 03_SimpleEvent/ROOT/config/events.php
$events['greeting.event'][1024] = ['Esit\Listener\OnGreetingListner', 'generateGreeting'];
$events['greeting.event'][2048] = ['Esit\Listener\OnGreetingListnerTwo', 'modifyGreeting']; # new!

return $events;
