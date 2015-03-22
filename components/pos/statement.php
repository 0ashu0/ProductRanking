<?php 

require_once 'POSTag.php';

$bigArray = array ();

$jsonPosFile = './data/posdata.json';
$jsonNegFile = './data/negdata.json';

$jsonPosFileP = './data/posdataPOS.json';
$jsonNegFileP = './data/negdataPOS.json';

$jsonContent = file_get_contents($jsonPosFile);
$jsonDecode = json_decode($jsonContent, TRUE);

$pos = new POSTag();

// echo '<pre>', var_dump($jsonDecode), '<pre/>';

foreach ($jsonDecode as $array) {
	$userID = $array['userID'];
    $reviewID = $array['reviewID'];
    $productID = $array['productID'];
    $currentSentence = $array['currentSentence'];

	$po = $array['currentSentence']['0'];
	$posArray = $pos->createTag($po);

	$currentArray = array(
		'reviewID' => $reviewID,
		'userID' => $userID,
		'productID' => $productID,
		'currentSentence' => $currentSentence,
		'posArray' => $posArray
	);

	$bigArray[] = $currentArray;
}
echo '<pre>', var_dump($bigArray), '<pre/>';


$jsonContent = file_get_contents($jsonPosFileP);
$jsonDecode = json_decode($jsonContent, TRUE);
$json = json_encode($bigArray, JSON_PRETTY_PRINT);
file_put_contents($jsonPosFileP,$json);
unset($jsonPosFileP);
unset($jsonContent);
unset($jsonDecode);
unset($json);


// $string = "Avatar had a surprisingly decent plot, and genuinely incredible special effects";
// $pos->createTag($string);

// echo '<br>';

// $string = "best for those who just want basic functionalities.";
// $pos->createTag($string);

// echo '<br>';

// $string = "It has a good quality camera";
// $pos->createTag($string);

?>