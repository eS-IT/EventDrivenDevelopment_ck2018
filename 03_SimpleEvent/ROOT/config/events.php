<?php
# 03_SimpleEvent/ROOT/config/events.php
$events['greeting.event'][1024] = ['Esit\Listener\OnGreetingListener', 'generateGreeting'];
$events['greeting.event'][512] = ['Esit\Listener\OnGreetingListenerTwo', 'modifyGreeting']; # new!

return $events;
