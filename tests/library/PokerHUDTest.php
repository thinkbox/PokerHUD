<?php
class PokerHUDTest extends PokerHUDMasterTest {
    private $hud;

    protected function setUp() {
        $this->hud = new Hud_PokerHUD();
    }
	
	public function testPokerHudDoitAvoirUnAttributDataPath() {
		$this->assertClassHasAttribute('data_path', 'Hud_PokerHUD');
	}
	
	public function testPokerHudDoitAvoirUnDataPathParDefaut() {
		$this->assertEquals(realpath(APPLICATION_PATH.'/../data'), realpath($this->hud->getDataPath()));
	}
	
	public function testPokerHudDoitPouvoirEtreInitialiseAvecUnRepertoireDataDifferent() {
		$hud = new Hud_PokerHUD($this->DATA_TEST_DIR);
		$this->assertEquals(realpath($this->DATA_TEST_DIR), realpath($hud->getDataPath()));
	}
	
	public function testPokerHudDoitAvoirLeDataPathParDefautSiInitialiseAvecUnRepertoireInexistant() {
		$hud = new Hud_PokerHUD('repertoire_data_inexistant');
		$this->assertEquals(realpath(APPLICATION_PATH.'/../data'), realpath($this->hud->getDataPath()));
	}
}