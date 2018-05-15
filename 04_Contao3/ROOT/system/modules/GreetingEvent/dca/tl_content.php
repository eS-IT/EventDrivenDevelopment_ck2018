<?php
# 04_Contao3/ROOT/system/modules/GreetingEvent/dca/tl_content.php
/* Set Tablename: tl_content */
$strName = 'tl_content';

/* Callback */
$GLOBALS['TL_DCA'][$strName]['list']['label']['label_callback'] = array('\Esit\GreetingEvent\Classes\Contao\Callbacks\MyCallback', 'myLabelCallback');

/* Palettes */
$GLOBALS['TL_DCA'][$strName]['palettes']['greeting'] = '{type_legend},type,headline,myname;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';

/* Fields */
$GLOBALS['TL_DCA'][$strName]['fields']['myname'] = array
(
   'label'                   => &$GLOBALS['TL_LANG'][$strName]['myname'],
   'exclude'                 => true,
   'inputType'               => 'text',
   'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
   'sql'                     => "varchar(255) NOT NULL default ''"
);
