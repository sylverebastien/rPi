<?php
class AppModel {
	protected $numbprises = 4;
	protected $capteurtemp = true;
	protected $capteurhum = true;
	protected $datas;

	public function ecrireDate(){
		$file = fopen('datas/serveur-lastrestart.txt', 'r+');
		$date = date('j/n \à H:i');
		fseek($file, 0);
		fputs($file, $date);
		fclose($file);
	}
	public function afficherDate() {
		$fichier = fopen('datas/serveur-lastrestart.txt', 'r+');
		$nb = fgets($fichier);
		fclose($fichier);
		return $nb;
	}
	public function augmenterUtilisation(){
		$fichier = fopen('datas/serveur-compteur.txt', 'r+');
		$nb = fgets($fichier);
		$nb++;
		fseek($fichier, 0);
		fputs($fichier, $nb);
		fclose($fichier);
	}
	public function afficherUtilisation() {
		$fichier = fopen('datas/serveur-compteur.txt', 'r+');
		$nb = fgets($fichier);
		fclose($fichier);
		return $nb;
	}

	public function validateTempResult() {
		if (isset($_GET['temp'])){
			$temp = $_GET['temp'];
			$temperatureprec = file_get_contents('datas/temperature.txt');
			$difference = $temp-$temperatureprec;
			return ($difference < 3 && $difference > -3) ? true : false;
		} else {
			return false;
		}
	}

	public function toggleChauffage($temp) {
		$statut= file_get_contents('datas/auto-chauffage.txt');
		if ($statut == 1) {
			if ($temp < 19) {
				$this->changerPrise('4','',1,'11100');
			}
			elseif ($temp >= 20) {
				$this->changerPrise('4','',0,'11100');
			}
		}
		if($temp>32){
			$this->sms->sendSMS('+33'.$this->numsms.'','Température anormale détectée dans la maison.','PREMIUM','Gladys');
		}
	}
	public function getCurrentState() {
		$this->Prise = new PriseModel();
		$this->Capteur = new CapteurModel();
		$states = [];
		for ($i = 1; $i <= $this->numbprises; $i++) {
			$states['lampe'.$i] = $this->Prise->find('lampe'.$i);
			$states['lampe'.$i] = ($states['lampe'.$i]['statut'] == 1) ? 'checked' : '';
		}
		$states['decodeur'] = $this->Prise->find('decodeur');
		$states['decodeur'] = ($states['decodeur']['statut'] == 1) ? 'checked' : '';

		if($this->capteurtemp){
			$states['temperature'] = $this->Capteur->get_temperature();
		}
		if($this->capteurhum){
			$states['humidite'] = $this->Capteur->get_humidite();
		}
		return $states;
	}

	protected function save() {
		$database = fopen(getcwd().'/datas/datas.json','r+');
		ftruncate($database,0);
		fwrite($database, json_encode($this->datas));
		fclose($database);
	}
	public function __construct() {
		$json = file_get_contents(getcwd().'/datas/datas.json');
		$this->datas = json_decode($json, true);
	}
}
