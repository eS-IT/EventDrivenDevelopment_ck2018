<?php
// CONTENT ELEMENTS
$GLOBALS['TL_CTE']['esit']['greeting'] = '\Esit\GreetingEvent\Classes\Contao\Elements\ContentGreeting';

// HOOKS
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('\Esit\GreetingEvent\Classes\Contao\Hooks\MyHook', 'myReplaceInsertTags');
