<?php
class Hud_PokerHUD {
	protected $data_path;

    function  __construct($data_dir = null) {
    	$this->data_path = file_exists($data_dir) ? $data_dir : APPLICATION_PATH.'/../data';
    }
	
	public function getDataPath() {
		return $this->data_path;
	}
}