<?php
class AutotoolsModel extends AppModel {
	public $name;
	public $statut;


	public function find($name) {
		foreach ($this->globals as $global_name => $global) {
			if ($global_name == $name) {
				$this->name = $global_name;
				$this->statut = $global['statut'];
				return $global;
			}
		}
	}

	public function toggle($val){
		$this->statut = $val;
		$this->update();
		if ($this->name == "alarme") {
			$this->Utils = new UtilsModel();
			$this->Utils->alarme($val);
		}
	}

	public function update() {
		$this->datas['globals'][$this->name]['statut'] = $this->statut;
		parent::save();
	}

	public function __construct() {
		parent::__construct();
		$this->globals = $this->datas['globals'];
	}

}
