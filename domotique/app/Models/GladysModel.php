<?php
class GladysModel extends AppModel {
	public function direPhrase($phrase) {
		echo "$phrase";
		`pico2wave -l fr-FR -w temp.wav "$phrase"`;
		exec('sudo omxplayer -o local temp.wav');
		exec('rm temp.wav');
	}
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
		/* Action for either reveil or chauffage */
		$this->update();
	}
	public function update() {
		$this->datas['globals'][$this->name]['statut'] = $this->statut;
		parent::save();
	}
	public function getCurrentState() {
		$this->Prise = new GladysModel();
		$states = [];
		$states['auto-chauffage'] = $this->find('auto-chauffage');
		$states['auto-chauffage'] = ($states['auto-chauffage']['statut'] == 1) ? 'checked' : '';
		$states['reveil'] = $this->find('reveil');
		$states['reveil'] = ($states['reveil']['statut'] == 1) ? 'checked' : '';
		$states['alarme'] = $this->find('alarme');
		$states['alarme'] = ($states['alarme']['statut'] == 1) ? 'checked' : '';
		return $states;
	}
	public function respond($tothisphrase) {
		$tothisphrase = ucfirst($tothisphrase);

		if ( stripos($tothisphrase, 'Dis') !== false && stripos($tothisphrase, 'dis') == 0 ) {
			$phraseadire = str_replace('Dis', '', $tothisphrase);
			$tothisphrase = 'Dis';
		}
		if ( stripos($tothisphrase, 'Dit') !== false && stripos($tothisphrase, 'dis') == 0 ) {
			$phraseadire = str_replace('Dit', '', $tothisphrase);
			$tothisphrase = 'Dis';
		}
		if ( stripos($tothisphrase, 'Exec') !== false && stripos($tothisphrase, 'Exec') == 0 ) {
			$phraseadire = str_replace('Exec ', '', $tothisphrase);
			$tothisphrase = 'Exec';
		}
		if ( stripos($tothisphrase, 'Vol') !== false && stripos($tothisphrase, 'Vol') == 0 ) {
			$phraseadire = str_replace('Vol ', '', $tothisphrase);
			$post = 'Vol';
		}
		switch($tothisphrase){

			case 'Radio':
			exec('sudo pkill mpg321');
			$this->direPhrase('Novaa, Brume, Jazz, France inter, Canuut. Radio p pour arrêter.');
			break;

			case 'Nova':
			exec('sudo pkill mpg321');
			$this->direPhrase('Lancement de Radio Novaa.');
			sleep(1);
			exec('sudo mpg321 "http://novazz.ice.infomaniak.ch/novazz-128"');
			break;

			case 'Brume':
			exec('sudo pkill mpg321');
			$this->direPhrase('Lancement de Radio Brume.');
			sleep(1);
			exec('sudo mpg321 "http://ice1.impek.com:80/radiobrume"');
			break;

			case 'Jazz':
			exec('sudo pkill mpg321');
			$this->direPhrase('Lancement de Jazz Radio.');
			sleep(1);
			exec('sudo mpg321 "http://jazzlounge.ice.infomaniak.ch/jazzlounge-high.mp3?.mp3"');
			break;

			case 'France inter':
			exec('sudo pkill mpg321');
			$this->direPhrase('Lancement de france inter.');
			sleep(1);
			exec('sudo mpg321 "http://audio.scdn.arkena.com/11008/franceinter-midfi128.mp3"');
			break;

			case 'Canut':
			exec('sudo pkill mpg321');
			$this->direPhrase('Lancement de Radio canuut.');
			sleep(1);
			exec('sudo mpg321 "http://live.francra.org:8000/radiocanut?.mp3"');
			break;

			case 'Vol':
			exec('sudo amixer cset numid=1 -- '.$phraseadire.'');
			break;

			case 'Radio p':
			exec('sudo pkill mpg321');
			sleep(1);
			$this->direPhrase('Arrêt de la radio.');
			break;


			case 'Hello':
			case 'Salut':
			case 'Bonjour':
			case 'bonjour':
			$this->direPhrase('Bonjour, comment allez vous ?');
			break;

			case 'Exec':
			echo exec($phraseadire);
			break;

			case 'Dis':
			$this->direPhrase($phraseadire);
			break;

			case 'Play':
			case 'Joue':
			case 'Musique':
			exec('sudo pkill mpg321');
			$this->Music->pause();
			$this->direPhrase('Je lance la musique.');
			sleep(1);
			$this->Music->play();
			break;

			case 'Off':
			case 'off':
			case 'stop':
			case 'Stop':
			case 'Pause':
			$this->Music->pause();
			break;

			case 'precedent':
			case 'Precedent':
			case 'précedent':
			$this->Music->precedent();
			break;

			case 'Suivant':
			$this->Music->suivant();
			break;

			case 'On':
			case 'Lumières':
			case 'Allume':
			case 'Allume les lumières':
			case 'Lumière':
			$this->Prise->allumertout();
			$this->direPhrase('Toutes les lumières ont été allumées.');
			break;

			case 'Eteins':
			case 'Eteindre':
			case 'Éteins':
			case 'Éteins les lumières':
			case 'Éteint les lumières':
			case 'Éteindre':
			$this->Prise->eteindretout();
			$this->direPhrase('Toutes les lumières ont été éteintes.');
			break;

			default:
			$this->direPhrase('Désolé, je n\'ai pas compris.');
			break;

		}
	}
	public function __construct() {
		parent::__construct();
		$this->globals = $this->datas['globals'];
	}

}
