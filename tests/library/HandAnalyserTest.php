<?php
class HandAnalyserTest extends PokerHUDMasterTest {
    private $hand;
	private $good_id_hand;
	private $players;
	private $stats;

    protected function setUp() {
		$this->good_id_hand = '83032964038';
        
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        
        if (file_exists($this->DATA_TEST_DIR.'/treated/'.$this->good_id_hand.'.txt')) {
        	rename($this->DATA_TEST_DIR.'/treated/'.$this->good_id_hand.'.txt', $this->DATA_TEST_DIR.'/'.$this->good_id_hand.'.txt');
        }
        
        if (!file_exists(APPLICATION_PATH.'/../data/'.$this->good_id_hand.'.txt')) {
        	file_put_contents(APPLICATION_PATH.'/../data/'.$this->good_id_hand.'.txt', file_get_contents($this->DATA_TEST_DIR.'/'.$this->good_id_hand.'.txt'));
        }
        
        $this->hand = new Hud_HandAnalyser($this->good_id_hand, $this->DATA_TEST_DIR);
        $this->players = $this->hand->getPlayers();
        $this->stats = $this->hand->getInfos();
        
        parent::setUp();
        $hand_mapper = new Application_Model_HandMapper();
    }
    
    public function testHandAnalyserDoitPouvoirEtreInitialiseAvecUnAutreRepertoireDataQueCeluiParDefaut() {
    	$hand_analyser = new Hud_HandAnalyser($this->good_id_hand, $this->DATA_TEST_DIR);
    	$this->assertEquals(realpath($this->DATA_TEST_DIR), realpath($hand_analyser->getDataPath()));
    }
    
    public function testHandAnalyserDoitAvoirUnDataPathParDefautSiInitialiseAvecUnRepertoireDataInexistant() {
    	$hand_analyser = new Hud_HandAnalyser($this->good_id_hand, 'repertoire_data_inexistant');
    	$this->assertEquals(realpath(APPLICATION_PATH.'/../data'), realpath($hand_analyser->getDataPath()));
    }
	
	public function testHandAnalyserDoitAvoirUnAttributIdHand() {
		$this->assertClassHasAttribute('id_hand', 'Hud_HandAnalyser');
	}

    public function testHandAnalyserDoitLeverUneExceptionSiInitialiserAvecUnIdDeMainIncorrect() {
        try {
			$hand = new Hud_HandAnalyser('id_incorrect');
		} catch (Exception $expected) {
			$expected_error = 'HandAnalyser.class.php : __construct(id_incorrect). Id incorrect.';
			$this->assertEquals($expected_error, $expected->getMessage());
			return;
		}
		$this->fail("Une exception n'a pas ete levee alors qu'on tente d'initialiser HandAnalyser avec un id de main incorrect.");
    }
	
	public function testHandAnalyserDoitAvoirCommeIdHandLaValeurPasseeEnParametreDuConstructeur() {
		$this->assertEquals($this->good_id_hand, $this->hand->getIdHand());
	}
	
	public function testHandAnalyserDoitAvoirUnAttributHand() {
		$this->assertClassHasAttribute('hand', 'Hud_HandAnalyser');
	}
	
	public function testGetHandDoitRetournerLeContenuDeLaMainContenuDansLeFichierCorrespondant() {
		$this->assertEquals(file_get_contents($this->DATA_TEST_DIR.'/'.$this->good_id_hand.'.txt'), $this->hand->getHand());
	}
	
	public function testGetPlayersDoitRetournerLaListeDesJoueurs() {
		$this->assertEquals(9, count($this->players));
		$this->assertEquals('rael81', $this->players[0]['player']);
		$this->assertEquals('ShortyFlix', $this->players[1]['player']);
		$this->assertEquals('BELLAURORA', $this->players[2]['player']);
		$this->assertEquals('evasion80', $this->players[3]['player']);
		$this->assertEquals('AlfaCephei', $this->players[4]['player']);
		$this->assertEquals('Tomukas XIII', $this->players[5]['player']);
		$this->assertEquals('Oshirasama', $this->players[6]['player']);
		$this->assertEquals('SERYO_GT', $this->players[7]['player']);
		$this->assertEquals('Dubaipoker80', $this->players[8]['player']);
	}
	
	public function testGetPlayersDoitIndiquerPourChaqueJoueurSaPositionInitialeAuDebutDeLaMain() {
		$this->assertEquals('button', $this->players[5]['position']);
		$this->assertEquals('small blind', $this->players[6]['position']);
		$this->assertEquals('big blind', $this->players[7]['position']);
		$this->assertEquals('under the gun', $this->players[8]['position']);
		$this->assertEquals('under the gun +1', $this->players[0]['position']);
		$this->assertEquals('middle', $this->players[1]['position']);
		$this->assertEquals('middle', $this->players[2]['position']);
		$this->assertEquals('hi-jack', $this->players[3]['position']);
		$this->assertEquals('cut-off', $this->players[4]['position']);
	}
	
	public function testHandAnalyserDoitAvoirUnAttributSeatButton() {
		$this->assertClassHasAttribute('seat_button', 'Hud_HandAnalyser');
	}
	
	public function testGetSeatButtonDoitRetournerLaValeurDuSiegeDuJoueurAuBouton() {
		$this->assertEquals(6, $this->hand->getSeatButton());
	}
	
	public function testGetPlayersDoitIndiquerLActionDeChaqueJoueurPreFlop() {
		$this->assertEquals('fold', $this->players['8']['action preflop']);
		$this->assertEquals('fold', $this->players['0']['action preflop']);
		$this->assertEquals('raise', $this->players['1']['action preflop']);
		$this->assertEquals('fold', $this->players['2']['action preflop']);
		$this->assertEquals('call', $this->players['3']['action preflop']);
		$this->assertEquals('fold', $this->players['4']['action preflop']);
		$this->assertEquals('fold', $this->players['5']['action preflop']);
		$this->assertEquals('fold', $this->players['6']['action preflop']);
		$this->assertEquals('fold', $this->players['7']['action preflop']);
	}
	
	public function testGetPlayersDoitIndiquerLActionDeChaqueJoueurAuFlop() {
		$this->assertEquals('', $this->players['8']['action flop']);
		$this->assertEquals('', $this->players['0']['action flop']);
		$this->assertEquals('bet, call', $this->players['1']['action flop']);
		$this->assertEquals('', $this->players['2']['action flop']);
		$this->assertEquals('raise', $this->players['3']['action flop']);
		$this->assertEquals('', $this->players['4']['action flop']);
		$this->assertEquals('', $this->players['5']['action flop']);
		$this->assertEquals('', $this->players['6']['action flop']);
		$this->assertEquals('', $this->players['7']['action flop']);
	}
	
	public function testGetPlayersDoitIndiquerLActionDeChaqueJoueurAuTurn() {
		$this->assertEquals('', $this->players['8']['action turn']);
		$this->assertEquals('', $this->players['0']['action turn']);
		$this->assertEquals('check, call', $this->players['1']['action turn']);
		$this->assertEquals('', $this->players['2']['action turn']);
		$this->assertEquals('bet', $this->players['3']['action turn']);
		$this->assertEquals('', $this->players['4']['action turn']);
		$this->assertEquals('', $this->players['5']['action turn']);
		$this->assertEquals('', $this->players['6']['action turn']);
		$this->assertEquals('', $this->players['7']['action turn']);
	}
	
	public function testGetPlayersDoitIndiquerLActionDeChaqueJoueurALaRiver() {
		$this->assertEquals('', $this->players['8']['action river']);
		$this->assertEquals('', $this->players['0']['action river']);
		$this->assertEquals('check, call', $this->players['1']['action river']);
		$this->assertEquals('', $this->players['2']['action river']);
		$this->assertEquals('bet', $this->players['3']['action river']);
		$this->assertEquals('', $this->players['4']['action river']);
		$this->assertEquals('', $this->players['5']['action river']);
		$this->assertEquals('', $this->players['6']['action river']);
		$this->assertEquals('', $this->players['7']['action river']);
	}
	
	public function testGetInfosDoitIndiquerLeNiveauDeTourDEnchere() {
		$this->assertEquals('III', $this->stats['level']);
	}
	
	public function testGetInfosDoitIndiquerLaSmallBlind() {
		$this->assertEquals(30, $this->stats['small blind']);
	}
	
	public function testGetInfosDoitIndiquerLaBigBlind() {
		$this->assertEquals(60, $this->stats['big blind']);
	}
	
	public function testGetInfosDoitIndiquerLesAntes() {
		$this->assertEquals(0, $this->stats['ante']);
	}
	
	public function testGetInfosDoitIndiquerLesVainqueurs() {
		$this->assertEquals('evasion80', $this->stats['winner']);
	}
	
	public function testGetInfosDoitIndiquerLeTypeDeVictoire() {
		$this->assertEquals('at showdown', $this->stats['type of winning']);
	}
	
	public function testGetInfosDoitIndiquerUnTypeDeVictoireBeforeShowdownSiLaMainNAPasEteJusquALAbattage() {
		$hand = new Hud_HandAnalyser('83426303179', $this->DATA_TEST_DIR);
		$stats = $hand->getInfos();
		$this->assertEquals('before showdown', $stats['type of winning']);
	}
	
	public function testGetInfosDoitIndiquerLesBonnesInfosSurUneAutreMain() {
		$hand = new Hud_HandAnalyser('83024714158', $this->DATA_TEST_DIR);
		$stats = $hand->getInfos();
		$this->assertEquals('X', $stats['level']);
		$this->assertEquals(100, $stats['small blind']);
		$this->assertEquals(200, $stats['big blind']);
		$this->assertEquals(25, $stats['ante']);
		$this->assertEquals('evasion80', $stats['winner']);
		$this->assertEquals('at showdown', $stats['type of winning']);
		
		$hand = new Hud_HandAnalyser('83033303416', $this->DATA_TEST_DIR);
		$stats = $hand->getInfos();
		$this->assertEquals('IV', $stats['level']);
		$this->assertEquals(40, $stats['small blind']);
		$this->assertEquals(80, $stats['big blind']);
		$this->assertEquals(0, $stats['ante']);
		$this->assertEquals('evasion80, Dubaipoker80', $stats['winner']);
		$this->assertEquals('at showdown', $stats['type of winning']);
		
		$hand = new Hud_HandAnalyser('83426359428', $this->DATA_TEST_DIR);
		$stats = $hand->getInfos();
		$this->assertEquals('IX', $stats['level']);
		$this->assertEquals(100, $stats['small blind']);
		$this->assertEquals(200, $stats['big blind']);
		$this->assertEquals(25, $stats['ante']);
		$this->assertEquals('diplodox500', $stats['winner']);
		$this->assertEquals('before showdown', $stats['type of winning']);
	}
	
	public function testHandAnalyserDoitAvoirUnAttributPlayersFromHand() {
		$this->assertClassHasAttribute('players_from_hand', 'Hud_HandAnalyser');
	}
	
	public function testHandAnalyserDoitAvoirUnAttributSeatsFromHand() {
		$this->assertClassHasAttribute('seats_from_hand', 'Hud_HandAnalyser');
	}
	
	public function testGetPlayersDoitIndiquerLeResultatPourChaqueJoueurDeLaMain() {
		$this->assertEquals('fold', $this->players[8]['resultat']);
		$this->assertEquals('fold', $this->players[0]['resultat']);
		$this->assertEquals('lost', $this->players[1]['resultat']);
		$this->assertEquals('fold', $this->players[2]['resultat']);
		$this->assertEquals('won at showdown', $this->players[3]['resultat']);
		$this->assertEquals('fold', $this->players[4]['resultat']);
		$this->assertEquals('fold', $this->players[5]['resultat']);
		$this->assertEquals('fold', $this->players[6]['resultat']);
		$this->assertEquals('fold', $this->players[7]['resultat']);
	}
	
	public function testGetPlayersDoitIndiquerUneVictoireALAbattageOuAvant() {
		$this->assertEquals('won at showdown', $this->players[3]['resultat']);
		
		$hand = new Hud_HandAnalyser('83426359428', $this->DATA_TEST_DIR);
		$players = $hand->getPlayers();
		$this->assertEquals('won before showdown', $players[0]['resultat']);
	}
	
	public function testSaveDoitEnregistrerLaMainEtLesActionsDesJoueurs() {
		$hand_mapper = $this->getMock('Application_Model_HandMapper', array('save'));
		$hand_mapper->expects($this->once())
					->method('save')
					->with($this->equalTo(new Application_Model_Hand(array(
			            'id_fichier'	=> '83032964038',
			            'level'			=> 'III',
			            'sb'			=> 30,
			            'bb'      		=> 60,
			            'ante'			=> 0,
			            'winner'		=> 'evasion80',
			            'content'		=> file_get_contents(dirname(__FILE__).'/../data/'.$this->good_id_hand.'.txt')
					))))
					->will($this->returnValue(4));
		
		$action_mapper = $this->getMock('Application_Model_ActionMapper', array('save'));
		$action_mapper->expects($this->exactly(9))
					  ->method('save');
		
		$action_mapper->expects($this->at(0))
					  ->method('save')
					  ->with($this->equalTo(new Application_Model_Action(array(
							'id_hand'			=> 4,
							'name_player'		=> 'rael81',
							'position'			=> 'under the gun +1', 
				    		'action_preflop'	=> 'fold',
				    		'action_flop'		=> '',
				    		'action_turn'		=> '',
				    		'action_river'		=> '', 
				    		'resultat'			=> 'fold'
					  ))));
		
		$action_mapper->expects($this->at(1))
					  ->method('save')
					  ->with($this->equalTo(new Application_Model_Action(array(
							'id_hand'			=> 4,
							'name_player'		=> 'ShortyFlix',
							'position'			=> 'middle', 
				    		'action_preflop'	=> 'raise',
				    		'action_flop'		=> 'bet, call',
				    		'action_turn'		=> 'check, call',
				    		'action_river'		=> 'check, call', 
				    		'resultat'			=> 'lost'
					  ))));
		
		$action_mapper->expects($this->at(2))
					  ->method('save')
					  ->with($this->equalTo(new Application_Model_Action(array(
							'id_hand'			=> 4,
							'name_player'		=> 'BELLAURORA',
							'position'			=> 'middle', 
				    		'action_preflop'	=> 'fold',
				    		'action_flop'		=> '',
				    		'action_turn'		=> '',
				    		'action_river'		=> '', 
				    		'resultat'			=> 'fold'
					  ))));
		
		$action_mapper->expects($this->at(3))
					  ->method('save')
					  ->with($this->equalTo(new Application_Model_Action(array(
							'id_hand'			=> 4,
							'name_player'		=> 'evasion80',
							'position'			=> 'hi-jack', 
				    		'action_preflop'	=> 'call',
				    		'action_flop'		=> 'raise',
				    		'action_turn'		=> 'bet',
				    		'action_river'		=> 'bet', 
				    		'resultat'			=> 'won at showdown'
					  ))));
		
		$action_mapper->expects($this->at(4))
					  ->method('save')
					  ->with($this->equalTo(new Application_Model_Action(array(
							'id_hand'			=> 4,
							'name_player'		=> 'AlfaCephei',
							'position'			=> 'cut-off', 
				    		'action_preflop'	=> 'fold',
				    		'action_flop'		=> '',
				    		'action_turn'		=> '',
				    		'action_river'		=> '', 
				    		'resultat'			=> 'fold'
					  ))));
		
		$action_mapper->expects($this->at(5))
					  ->method('save')
					  ->with($this->equalTo(new Application_Model_Action(array(
							'id_hand'			=> 4,
							'name_player'		=> 'Tomukas XIII',
							'position'			=> 'button', 
				    		'action_preflop'	=> 'fold',
				    		'action_flop'		=> '',
				    		'action_turn'		=> '',
				    		'action_river'		=> '', 
				    		'resultat'			=> 'fold'
					  ))));
		
		$action_mapper->expects($this->at(6))
					  ->method('save')
					  ->with($this->equalTo(new Application_Model_Action(array(
							'id_hand'			=> 4,
							'name_player'		=> 'Oshirasama',
							'position'			=> 'small blind', 
				    		'action_preflop'	=> 'fold',
				    		'action_flop'		=> '',
				    		'action_turn'		=> '',
				    		'action_river'		=> '', 
				    		'resultat'			=> 'fold'
					  ))));
		
		$action_mapper->expects($this->at(7))
					  ->method('save')
					  ->with($this->equalTo(new Application_Model_Action(array(
							'id_hand'			=> 4,
							'name_player'		=> 'SERYO_GT',
							'position'			=> 'big blind', 
				    		'action_preflop'	=> 'fold',
				    		'action_flop'		=> '',
				    		'action_turn'		=> '',
				    		'action_river'		=> '', 
				    		'resultat'			=> 'fold'
					  ))));
		
		$action_mapper->expects($this->at(8))
					  ->method('save')
					  ->with($this->equalTo(new Application_Model_Action(array(
							'id_hand'			=> 4,
							'name_player'		=> 'Dubaipoker80',
							'position'			=> 'under the gun', 
				    		'action_preflop'	=> 'fold',
				    		'action_flop'		=> '',
				    		'action_turn'		=> '',
				    		'action_river'		=> '', 
				    		'resultat'			=> 'fold'
					  ))));
		
		$this->hand->save($hand_mapper, $action_mapper);
	}
	
	public function testGetInfosDoitDonnerVainqueurLeJoueurNAyantPasEteSuivi() {
		$hand = new Hud_HandAnalyser('83426141599', $this->DATA_TEST_DIR);
		$stats = $hand->getInfos();
		$this->assertEquals('VIII', $stats['level']);
		$this->assertEquals(75, $stats['small blind']);
		$this->assertEquals(150, $stats['big blind']);
		$this->assertEquals(20, $stats['ante']);
		$this->assertEquals('micha06200', $stats['winner']);
	}
	
	public function testGetPlayersDoitRetournerLActionUniqueSansVirguleSiUnJoueurARelanceEtNAPasEteSuiviEtQuIlMontreOuPasSesCartes() {
		$hand = new Hud_HandAnalyser('83797553386', $this->DATA_TEST_DIR);
		$players = $hand->getPlayers();
		$this->assertEquals('raise', $players[3]['action preflop']);
		
		$hand = new Hud_HandAnalyser('83426458653', $this->DATA_TEST_DIR);
		$players = $hand->getPlayers();
		$this->assertEquals('raise', $players[1]['action flop']);
		
		$hand = new Hud_HandAnalyser('83426303179', $this->DATA_TEST_DIR);
		$players = $hand->getPlayers();
		$this->assertEquals('bet', $players[1]['action turn']);
		
		$hand = new Hud_HandAnalyser('83426359428', $this->DATA_TEST_DIR);
		$players = $hand->getPlayers();
		$this->assertEquals('bet', $players[0]['action river']);
	}
	
	public function testSaveDoitDeplacerLeFichierDeLaMainAnalyseeDansUnDossierDeMainsTraitees() {
		$hand_mapper = $this->getMock('Application_Model_HandMapper', array('save'));
		$hand_mapper->expects($this->once())
					->method('save');
		
		$action_mapper = $this->getMock('Application_Model_ActionMapper', array('save'));
		$action_mapper->expects($this->exactly(9))
					  ->method('save');
		
		$this->hand->save($hand_mapper, $action_mapper);
		
		$this->assertFileNotExists(APPLICATION_PATH.'/../tests/data/'.$this->good_id_hand.'.txt');
		$this->assertFileExists(APPLICATION_PATH.'/../tests/data/treated/'.$this->good_id_hand.'.txt');
	}
	
	public function testViderLeFichierDataPourLeTest1() {
		if (file_exists(APPLICATION_PATH.'/../data/'.$this->good_id_hand.'.txt')) {
			$result = unlink(APPLICATION_PATH.'/../data/'.$this->good_id_hand.'.txt');
		}
		$this->assertTrue($result);
	}
}