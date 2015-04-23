<?php
/**
 * Created by PhpStorm.
 * User: ASHUTOSH
 * Date: 4/17/2015
 * Time: 12:57 AM
 */

//echo '<pre>', var_dump($bigArray), '<pre/>';

$count = 1;
foreach($bigArray as $array)
{
    echo "Rank: " . $count; echo '<br>';
    echo "productID: "; echo $array['0']; echo '<br>';
    echo "product Name: "; echo $array['1']; echo '<br>';
    echo "product Price: "; echo $array['2']; echo '<br>'; echo '<br>';
    $count = $count + 1;
}