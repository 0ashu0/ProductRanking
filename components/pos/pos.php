<?php

require('PosTagger.php');

$query = "Welcome to the world of programming.";
$pieces = array();
$pieces = explode(" ", $query);
$arrlength = count($pieces);

for($x=0;$x<$arrlength;$x++)
{
	echo $pieces[$x];
	echo "<br>";
}

echo '<br>';

$tagger = new PosTagger('lexicon.txt');
$tags = $tagger->tag($query);
printTag($tags);

echo "Tags:";
function printTag($tags)
{
	foreach($tags as $t)
	{
		echo $t['token'] . "/" . $t['tag'] . " ";
		echo "<br>";
	}
	echo "<br>";
}

echo '<pre>', var_dump($tags), '<pre/>';

?>