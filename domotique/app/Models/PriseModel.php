<?php
class PriseModel extends AppModel {
	public $name;
	public $prise_number;
	public $code;
	public $statut;
	public $count;


	public function toggle($val){
		$this->statut = $val;
		exec('sudo /home/pi/rcswitch-pi/./send '.$this->code.' '.$this->prise_number.' '.$val.'');
		$this->update();
	}

	private function update() {
		$this->datas['devices'][$this->name]['statut'] = $this->statut;
		$this->datas['devices'][$this->name]['count'] = $this->count+1;
		parent::save();
	}

	public function toggleSeveral($prises,$val){
		foreach($prises as $prise) {
			$this->toggle($prise,'lampe'.$prise,$val,$this->code);
		}
	}

	public function getStatsForEach() {
		$nb = [];
		for ($i = 1; $i <= $this->numbprises; $i++) {
			$nb['lampe'.$i] = $this->find('lampe'.$i);
			$nb['lampe'.$i] = ($nb['lampe'.$i]['count']);
		}
		$nb['decodeur'] = $this->find('decodeur');
		$nb['decodeur'] = ($nb['decodeur']['count']);
		$nb['pc'] = $this->find('pc');
		$nb['pc'] = ($nb['pc']['count']);
		$nb['serveur'] = date('j/n \à H:i');
		$nb['datereboot'] = date('j/n \à H:i');
		return $nb;
	}

	public function find($name) {
		foreach ($this->prises as $prise_name => $prise) {
			if ($prise_name == $name) {
				$this->name = $prise_name;
				$this->code = $prise['code'];
				$this->prise_number = $prise['prise_number'];
				$this->statut = $prise['statut'];
				$this->count = $prise['count'];
				return $prise;
			}
		}
	}

	public function __construct() {
		parent::__construct();
		$this->prises = $this->datas['devices'];
	}
}
