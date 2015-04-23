<?php
/**
 * Created by PhpStorm.
 * User: ashutosh
 * Date: 1/25/2015
 * Time: 10:20 PM
 */


//echo '<pre>', var_export($op), '<pre/>';

//echo '<pre>', var_export($string), '<pre/>';
//echo '<pre>', var_export($result), '<pre/>';

//echo $string;
echo '<br/>';
//echo $result;


//echo '<pre>', var_export($reviews), '<pre/>';

//echo '<pre>', var_export($arrayObject), '<pre/>';

echo '<br>';

//echo '<pre>', var_export($bigArray), '<pre/>';

foreach($bigArray as $array)
{
    echo '<br>';
    echo $array['allReviews']['first']['0'];
    echo '<br>';
    echo $array['allReviews']['first']['1'];
    echo '<br>';
}
