<?php
class CollectorTest extends PokerHUDMasterTest {
    private $collector;

    protected function setUp() {
        if (file_exists($this->DATA_TEST_DIR.'/treated/83032964038.txt')) {
        	rename($this->DATA_TEST_DIR.'/treated/83032964038.txt', $this->DATA_TEST_DIR.'/83032964038.txt');
        }
        
        $this->collector = new Hud_Collector($this->HISTORIQUE_PATH, $this->DATA_TEST_DIR);
        file_put_contents($this->HISTORIQUE_PATH, file_get_contents(APPLICATION_PATH.'/../historique-sauvegarde.txt'));
    }
    
    public function testCollectorDoitPouvoirEtreInitialiseAvecUnAutreRepertoireDataQueCeluiParDefaut() {
    	$collector = new Hud_Collector($this->HISTORIQUE_PATH, $this->DATA_TEST_DIR);
    	$this->assertEquals(realpath($this->DATA_TEST_DIR), realpath($collector->getDataPath()));
    }
    
    public function testCollectorDoitAvoirUnDataPathParDefautSiInitialiseAvecUnRepertoireDataInexistant() {
    	$collector = new Hud_Collector($this->HISTORIQUE_PATH, 'repertoire_data_inexistant');
    	$this->assertEquals(realpath(APPLICATION_PATH.'/../data'), realpath($collector->getDataPath()));
    }
	
	public function testCollectorDoitAvoirUnAttributHistoriquePath() {
		$this->assertClassHasAttribute('historique_path', 'Hud_Collector');
	}

    public function testCollectorDoitLeverUneExceptionSiInitialiserAvecUnFichierInexistant() {
        try {
			$collector = new Hud_Collector('historique_inexistant');
		} catch (Exception $expected) {
			$expected_error = 'Collector.class.php : __construct(historique_inexistant). Historique inexistant.';
			$this->assertEquals($expected_error, $expected->getMessage());
			return;
		}
		$this->fail("Une exception n'a pas ete levee alors qu'on tente d'initialiser Collector avec un historique de mains inexistant.");
    }
	
	public function testCollectorDoitAvoirCommeHistoriquePathLaValeurPasseeEnParametreDuConstructeur() {
		$this->assertEquals($this->HISTORIQUE_PATH, $this->collector->getHistoriquePath());
	}
	
	public function testGetHandsDoitRetourner0SiLeFichierHistoriqueEstVide() {
		$collector = new Hud_Collector(APPLICATION_PATH.'/../historique-vide.txt');
		$this->assertEquals(0, $collector->getHands());
	}
	
	public function testGetHandsDoitRetournerLeNombreDeMainsTraitees() {
		$this->assertEquals(3, $this->collector->getHands());
	}
	
	public function testGetHandsDoitCreerLesFichiersDansDataCorrespondantAuxMainsTraitees() {
		$this->collector->getHands();
		$scan = scandir($this->DATA_TEST_DIR);
		$this->assertContains('83024714158.txt', $scan);
		$this->assertContains('83032964038.txt', $scan);
		$this->assertContains('83033303416.txt', $scan);
	}
	
	public function testGetHandsDoitRemplirLesFichiersDansDataAvecLHistoriqueDeLaMainCorrespondante() {
		$this->collector->getHands();
		$this->assertFileEquals($this->DATA_TEST_DIR.'/83024714158.txt', $this->collector->getDataPath().'/83024714158.txt');
		$this->assertFileEquals($this->DATA_TEST_DIR.'/83032964038.txt', $this->collector->getDataPath().'/83032964038.txt');
		$this->assertFileEquals($this->DATA_TEST_DIR.'/83033303416.txt', $this->collector->getDataPath().'/83033303416.txt');
	}
	
	public function testGetHandsDoitViderLeFichierDHistoriqueParDefaut() {
		$this->collector->getHands();
		$this->assertEmpty(file_get_contents($this->collector->getHistoriquePath()));
	}
	
	public function testGetHandsNeDoitPasViderLeFichierDHistoriqueSiDemande() {
		$this->collector->getHands(false);
		$this->assertFileEquals(APPLICATION_PATH.'/../historique-sauvegarde.txt', $this->collector->getHistoriquePath());
	}
}