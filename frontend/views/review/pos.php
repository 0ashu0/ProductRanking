<?php
/**
 * Created by PhpStorm.
 * User: ASHUTOSH
 * Date: 3/13/2015
 * Time: 12:07 PM
 */

echo '<pre>', var_export($bigArray), '<pre/>';

foreach($bigArray as $array)
{
    echo '<hr>';
    var_export($array);
}