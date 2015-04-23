<?php
namespace components;
use yii\base\Object;

require('PosTagger.php');
class POSTag extends Object
{
	public $query;
	
	public function createTag($query)
	{
		$pieces = explode(" ", $query);
		$arrLength = count($pieces);
//		$lexicon = './../../components/pos/lexicon.txt';
		$lexicon = (__DIR__.'./lexicon.txt' );
		$tagger = new PosTagger($lexicon);
		$tags = $tagger->tag($query);

		echo "<table><tr><th>Token</th> <th>Tag</th></tr>";
		foreach ($tags as $t) {
//			echo  $t['token'] . "/" . $t['tag'] . " ";


			echo "<table><tr><td> " . $t['token'] . " </td> <td> " . $t['tag'] . " </td></tr>";
		}
		echo "</table>";

		return $tags;
	}
}

?>