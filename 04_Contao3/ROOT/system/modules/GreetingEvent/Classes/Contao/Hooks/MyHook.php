<?php
# 04_Contao3/ROOT/system/moduel/ERWEITERUNG/src/MyHook.php
namespace Esit\GreetingEvent\Classes\Contao\Hooks;

class MyHook
{
    public function myReplaceInsertTags($strTag)
    {
        if ($strTag == 'mytag') {
            return 'mytag replacement';
        }

        return false;
    }
}
