<?php
# ROOT/system/moduel/GreetingEvent/Classes/Contao/Callbacks/MyCallback.php
namespace Esit\GreetingEvent\Classes\Contao\Callbacks;

class MyCallback
{
    public function myLabelCallback($row, $label){
        // Do something
        $newLabel = $row['id'] . ': ' . $label;
        return $newLabel;
    }
}
