<?php
/**
 * @package     ck2018
 * @filesource  SlideLoader.php
 * @version     1.0.0
 * @since       25.04.18 - 18:20
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     EULA
 */
namespace Esit\Classes;

/**
 * Class SlideLoader
 * @package Esit\Classes
 */
class SlideLoader
{


    public static function loadSlides()
    {
        $path       = str_replace('Classes', 'Slides', __DIR__);
        $files      = glob($path . '/*/*.md');
        $content    = '';

        foreach ($files as $file) {
            $file       = (is_array($file)) ? array_shift($file) : $file;
            $content   .= file_get_contents($file) . "\n---\n";
        }

        $content = substr($content, 0, -5);
        return $content;
    }
}
