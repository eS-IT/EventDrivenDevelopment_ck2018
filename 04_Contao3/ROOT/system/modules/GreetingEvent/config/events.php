<?php
# 04_Contao3/ROOT/system/modules/GreetingEvent/config/events.php
$events['greeting.event'][1024] = ['Esit\GreetingEvent\Classes\Listener\OnGreetingListener', 'generateGreeting'];
$events['greeting.event'][2048] = ['Esit\GreetingEvent\Classes\Listener\OnGreetingListenerTwo', 'generateMessage'];

return $events;