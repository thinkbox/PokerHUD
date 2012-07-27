<?php
class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$form = new Zend_Form();
    	$form->setAttrib('id', 'search');
    	$form->addElement('text', 'player', array('label' => 'Nom :'));
    	$form->addElement('submit', 'chercher');
    	
    	$this->view->form = $form;
        // action body
    	
    	// recuperation des mains a partir d'un historique
    	/*$collecteur_de_mains = new Hud_Collector(APPLICATION_PATH.'/../data/historique-592658986.txt');
    	$nb_mains_recuperees = $collecteur_de_mains->getHands(false);
    	echo '<pre>'.var_export($nb_mains_recuperees, true).'</pre>';*/
    	
    	// enregistrement en bdd des actions et des mains
    	/*$compteur = 0;
    	$dir = APPLICATION_PATH.'/../data';
    	// Ouvre un dossier bien connu, et liste tous les fichiers
    	if (is_dir($dir)) {
    		if ($dh = opendir($dir)) {
    			while (($file = readdir($dh)) !== false && $compteur < 30) {
    				$compteur++;
    				if (!is_dir($dir.'/'.$file)) {
    					//echo "fichier : $file - ";
    					if (preg_match('/^([0-9]*).txt/', $file, $id) == 1) {
    						echo "id : $id[1] ";
    						$hand = new Hud_HandAnalyser($id[1]);
    						$hand->save();
    					}
    					echo " --- ";
    				}
    			}
    			closedir($dh);
    		}
    	}*/
    	
    	// enregistrement en bdd des joueurs et des stats sur les actions non traitees
    	/*$compteur = 0;
    	$content = '';
    	$stat_collector = new Hud_StatCollector();
    	while ($compteur < 30) {
    		$stat_collector->collect();
    		$content .= '<pre>'.var_export($stat_collector->getAction(), true).'</pre>';
    		$content .= '<pre>'.var_export($stat_collector->getIdJoueur(), true).'</pre>';
    		$compteur++;
    	}
    	
    	echo $content;*/
    	
    	// nouveau traitement des anciennes mains deja analysees mais dont on veut obtenir une nouvelle stat
    	/*$stat_collector = new Hud_StatCollector();
    	$compteur = 0;
    	$content = '';
    	while ($compteur < 30) {
    		$action_traite = $stat_collector->collectAgain('nombre de mains jouees et foldees preflop' , '2012-07-27 13:51:59');
    		$content .= '<pre>'.var_export($action_traite, true).'</pre>';
    		$compteur++;
    	}
    	
    	echo $content;*/
    	
    	
    	/*$config = array (
		    'ssl' => 'tls',
		    'port' => 587,
		    'auth' => 'login',
		    'username' => 'vincent.guglielmi@gmail.com',
		    'password' => ''
		);
		 
		$mailTransport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
    	
    	$mail = new Zend_Mail();
		$mail->setBodyText($content);
		$mail->setBodyHtml($content);
    	$mail->setFrom('noreply@pokerhud.fr', 'pokerhud');
    	$mail->addTo('externe.vincent.guglielmi@cellfishmedia.fr', 'Vincent Guglielmi');
    	$mail->setSubject('[POKERHUD CRON] Suivi cron importation des données PokerHud');
    	$mail->send($mailTransport);*/
    	
    	// calcul et affichage des stats
    	/*$mapper = new Application_Model_PlayerMapper();
    	$statmapper = new Application_Model_StatMapper();
    	
    	$infos = array();
    	$players = $mapper->getDbTable()->fetchAll();
    	foreach ($players as $player) {
    		$stats = $statmapper->getDbTable()->fetchAll('id_player = '.$player->__get('id'));
    		$infos[$player->__get('name')] = array();
    		$infos[$player->__get('name')]['total'] = $player->__get('nb_hands');
    		foreach ($stats as $stat) {
    			$infos[$player->__get('name')][$stat->__get('type')] = (int) $stat->__get('valeur');
    		}
    	}
    	
    	foreach ($infos as $key => $info) {
    		echo '<h3>'.$key.'</h3>';
    		echo 'total de mains : '.$info['total'].'<br />';
    		
    		$flops_vus = array_key_exists('nombre de flops vus', $info) ? floor($info['nombre de flops vus']*100/$info['total']) : 0;
    		$flops_vus_bouton = array_key_exists('nombre de flops vus au bouton', $info) ? floor($info['nombre de flops vus au bouton']*100/$info['nombre de flops vus']) : 0;
    		$flops_vus_sb = array_key_exists('nombre de flops vus en small blind', $info) ? floor($info['nombre de flops vus en small blind']*100/$info['nombre de flops vus']) : 0;
    		$flops_vus_bb = array_key_exists('nombre de flops vus en big blind', $info) ? floor($info['nombre de flops vus en big blind']*100/$info['nombre de flops vus']) : 0;
    		$flops_vus_autre = 100 - ($flops_vus_bouton + $flops_vus_sb + $flops_vus_bb);
    		$mains_jouees_mais_foldees_preflop = array_key_exists('nombre de mains jouees et foldees preflop', $info) ? $info['nombre de mains jouees et foldees preflop'] : 0;
    		$mains_jouees = floor(($info['nombre de flops vus'] + $mains_jouees_mais_foldees_preflop)*100/$info['total']);
    		$mains_gagnees_at_showdown = array_key_exists('nombre de mains gagnees au showdown', $info) ? $info['nombre de mains gagnees au showdown'] : 0;
    		$mains_gagnees_before_showdown = array_key_exists('nombre de mains gagnees avant le showdown', $info) ? $info['nombre de mains gagnees avant le showdown'] : 0;
    		$mains_gagnees_total = $mains_gagnees_at_showdown + $mains_gagnees_before_showdown;
    		$mains_gagnees = floor($mains_gagnees_total*100/$info['total']);
    		$mains_gagnees_flop = floor($mains_gagnees_total*100/$info['nombre de flops vus']);
    		$mains_gagnees_flop_at_showdown = floor($mains_gagnees_at_showdown*100/$mains_gagnees_total);
    		$mains_gagnees_flop_before_showdown = floor($mains_gagnees_before_showdown*100/$mains_gagnees_total);
    		$mains_perdues_show = array_key_exists('nombre de mains perdues au showdown', $info) ? $info['nombre de mains perdues au showdown'] : 0;
    		$mains_perdues_at_showdown = floor($mains_perdues_show*100/$info['nombre de flops vus']);
    		$mains_gagnees_uniquement_showdown = floor($mains_gagnees_at_showdown*100/($mains_gagnees_at_showdown+$mains_perdues_show));
    		
    		echo '% de mains jouées : '.$mains_jouees. ' % ('.($info['nombre de flops vus'] + $mains_jouees_mais_foldees_preflop).'/'.$info['total'].')<br />';
    		echo '% de flops vus : '.$flops_vus.' % ('.$info['nombre de flops vus'].'/'.$info['total'].')<br />';
    		echo '% de flops vus au bouton : '.$flops_vus_bouton.' %<br />';
    		echo '% de flops vus en small blind : '.$flops_vus_sb.' %<br />';
    		echo '% de flops vus en big blind : '.$flops_vus_bb.' %<br />';
    		echo '% de flops vus autre position : '.$flops_vus_autre.' %<br />';
    		echo '% de mains gagnées : '.$mains_gagnees.' %<br />';
    		echo '% de mains gagnées en ayant vu le flop : '.$mains_gagnees_flop.' %<br />';
    		echo '% de mains gagnées a l\'abattage : '.$mains_gagnees_flop_at_showdown.' %<br />';
    		echo '% de mains gagnées avant l\'abattage : '.$mains_gagnees_flop_before_showdown.' %<br />';
    		echo '% de mains perdues a l\'abattage : '.$mains_perdues_at_showdown.' %<br />';
    		echo '% de mains gagnées a l\'abattage (uniquement sur les mains étant allées à l\'abattage : '.$mains_gagnees_uniquement_showdown.' %)<br />';
    	}*/
    }
}