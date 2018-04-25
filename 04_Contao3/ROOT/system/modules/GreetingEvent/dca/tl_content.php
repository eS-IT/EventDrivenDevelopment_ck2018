<?php
/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 *
 * @package     ck2018
 * @filesource  tl_content.php
 * @version     1.0.0
 * @since       24.04.18 - 14:36
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     EULA
 */

/**
 * Set Tablename: tl_content
 */
$strName = 'tl_content';

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