<?php
require_once 'library/PokerHUD.php';
require_once 'library/Collector.php';
require_once 'library/HandAnalyser.php';

/*$collector = new Collector('historique-588308005.txt');
$collector->getHands();*/

$hand = new HandAnalyser('83426525740');

pre_dump($hand->getInfos());

pre_dump($hand->getPlayers());

//$hand = new HandAnalyser('83032964038');
//pre_dump($hand->getInfos());

function pre_dump($var) {
	echo '<pre>'.var_export($var, true).'</pre>';
}