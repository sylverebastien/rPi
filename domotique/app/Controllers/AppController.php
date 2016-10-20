<?php
class AppController {

	public function index() {
		$states = $this->App->getCurrentState();
		include($this->layoutpath.'header.php');
		include($this->viewpath.'index-view.php');
		include($this->layoutpath.'footer.php');
	}

	public function temp() {
		if ($this->App->validateTempResult()) {
			$temperature = $_GET['temp'];
			$this->Capteur->find('temperature');
			$this->Capteur->changeValue($temperature);
			$this->App->toggleChauffage($temp);
		}
	}

	public function allumertout() {
		$this->Prise->code = '10101';
		$this->Prise->toggleSeveral(array(1,2,3,4),1);
		$this->Gladys->direPhrase('C\'est fait.');
	}

	public function eteindretout() {
		$this->Prise->code = '10101';
		$this->Prise->toggleSeveral(array(1,2,3,4),0);
		$this->Gladys->direPhrase('C\'est fait.');
	}

	public function serveur() {
		$this->Gladys->direPhrase('Préparation au redémarrage du serveur.');
		sleep(2);
		$this->App->ecrireDate();
		$this->App->augmenterUtilisation();
		exec('sudo reboot');
	}

	public function pc() {
		if($this->val) {
			$this->Pc->turnOn();
		}
		else {
			$this->Pc->turnOff();
		}
	}

	public function reveil() {
		$this->Autotools->find('reveil');
		$this->Autotools->toggle($this->val);
	}

	public function alarme() {
		$this->Autotools->find('alarme');
		$this->Autotools->toggle($this->val);
	}

	public function stats() {
		$states = $this->App->getCurrentState();
		$nb = $this->Prise->getStatsForEach();
		include $this->viewpath.'stats-view.php';
	}

	public function camera() {
		include $this->viewpath.'musique-view.php';
	}

	public function autochauffage() {
		$this->Autotools->find('auto-chauffage');
		$this->Autotools->toggle($this->val);
		//$this->temp();
	}

	public function chauffage() {
		$this->Prise->find('chauffage');
		$this->Prise->toggle($this->val);
	}

	public function ping() {
		$checked = $this->Pc->ping();
		include($this->viewpath.'ping-view.php');
	}

	public function lampe($lampe_to_toggle) {
		$this->Prise->find('lampe'.$lampe_to_toggle);
		$this->Prise->toggle($this->val);
	}

	public function hp() {
		$this->Prise->find('hp');
		$this->Prise->toggle($this->val);
	}

	public function bt() {
		$this->Prise->find('bt');
		$this->Prise->toggle($this->val);
	}

	public function routeur() {
		include $this->viewpath.'gladys-view.php';
	}

	public function glad() {
		$question = $_POST['text'];
		$this->Gladys->respond($question);
	}

	public function __construct() {
		$this->layoutpath = getcwd().'/app/Views/layout/';
		$this->viewpath = getcwd().'/app/Views/';
		$this->rootpath = getcwd().'/';

		$models = scandir(getcwd().'/app/Models/', 1);

		foreach ($models as $model) {
			if (!is_dir(getcwd().'/app/Models/'.$model)) {
				$instance_name = str_replace('Model.php','',$model);
				$model_name = str_replace('.php','',$model);
				$this->$instance_name = new $model_name;
			}
		}

		if ( isset($_POST['val']) )
		$this->val=$_POST['val'];
		elseif ( isset($_GET['val']) )
		$this->val=$_GET['val'];

	}
}
