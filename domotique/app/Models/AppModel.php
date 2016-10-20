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
		fseek($fichier, 0);
		fputs($fichier, $nb+1);
		fclose($fichier);
	}

	public function afficherUtilisation() {
		$fichier = fopen('datas/serveur-compteur.txt', 'r+');
		$nb = fgets($fichier);
		fclose($fichier);
		return $nb;
	}

	public function validateTempResult() {
		$this->Capteur = new CapteurModel();
		if (isset($_GET['temp'])){
			$temp = $_GET['temp'];
			$temperatureprec = $this->Capteur->get_temperature();
			$difference = $temp-$temperatureprec;
			return ($difference < 3 && $difference > -3) ? true : false;
		} else {
			return false;
		}
	}

	public function toggleChauffage($temp) {
		$states = [];
		$this->Autotools = new AutotoolsModel();
		$states['auto-chauffage'] = $this->Autotools->find('auto-chauffage');
		$this->Prise = new PriseModel();
		$states['chauffage'] = $this->Prise->find('chauffage');
		if ($states['auto-chauffage']['statut'] == 1) {
			if ($temp < 19 && $states['chauffage']['statut'] == 0) {
				$this->Prise->find('chauffage');
				$this->Prise->toggle(1);
			}
			elseif ($temp >= 20 && $states['chauffage']['statut'] == 1) {
				$this->Prise->find('chauffage');
				$this->Prise->toggle(0);
			}
		}
		if($temp>32 && $states['chauffage']['statut'] == 1){
			$this->Prise->find('chauffage');
			$this->Prise->toggle(0);
			//Mail envoi probleme chaleur detecte
		}
	}

	public function getCurrentState() {
		$this->Prise = new PriseModel();
		$this->Capteur = new CapteurModel();
		$this->Autotools = new AutotoolsModel();

		$states = [];

		for ($i = 1; $i <= $this->numbprises; $i++) {
			$states['lampe'.$i] = $this->Prise->find('lampe'.$i);
			$states['lampe'.$i] = ($states['lampe'.$i]['statut'] == 1) ? 'checked' : '';
		}

		$states['hp'] = $this->Prise->find('hp');
		$states['hp'] = ($states['hp']['statut'] == 1) ? 'checked' : '';

		$states['bt'] = $this->Prise->find('bt');
		$states['bt'] = ($states['bt']['statut'] == 1) ? 'checked' : '';

		$states['chauffage'] = $this->Prise->find('chauffage');
		$states['chauffage'] = ($states['chauffage']['statut'] == 1) ? 'checked' : '';

		if($this->capteurtemp){
			$states['temperature'] = $this->Capteur->get_temperature();
		}
		if($this->capteurhum){
			$states['humidite'] = $this->Capteur->get_humidite();
		}

		$states['auto-chauffage'] = $this->Autotools->find('auto-chauffage');
		$states['auto-chauffage'] = ($states['auto-chauffage']['statut'] == 1) ? 'checked' : '';
		$states['reveil'] = $this->Autotools->find('reveil');
		$states['reveil'] = ($states['reveil']['statut'] == 1) ? 'checked' : '';
		$states['alarme'] = $this->Autotools->find('alarme');
		$states['alarme'] = ($states['alarme']['statut'] == 1) ? 'checked' : '';

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
