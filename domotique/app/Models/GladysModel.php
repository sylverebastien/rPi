<?php
class GladysModel extends AppModel {
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
			echo "Nova, Brume, Jazz, France inter, Canut.\n";
			echo "Radio p pour arrêter";
			exec('pico2wave -l fr-FR -w temp.wav "Novaa, Brume, Jazz, France inter, Canuut."');
			exec('sudo omxplayer -o local temp.wav');
			exec('rm temp.wav');
			break;

			case 'Nova':
			exec('sudo pkill mpg321');
			exec('pico2wave -l fr-FR -w temp.wav "Lancement de Radio Novaa."');
			exec('sudo omxplayer -o local temp.wav');
			exec('rm temp.wav');
			sleep(1);
			exec('sudo mpg321 "http://novazz.ice.infomaniak.ch/novazz-128"');
			break;

			case 'Brume':
			exec('sudo pkill mpg321');
			exec('pico2wave -l fr-FR -w temp.wav "Lancement de Radio Brume."');
			exec('sudo omxplayer -o local temp.wav');
			exec('rm temp.wav');
			sleep(1);
			exec('sudo mpg321 "http://ice1.impek.com:80/radiobrume"');
			break;

			case 'Jazz':
			exec('sudo pkill mpg321');
			exec('pico2wave -l fr-FR -w temp.wav "Lancement de Jazz Radio."');
			exec('sudo omxplayer -o local temp.wav');
			exec('rm temp.wav');
			sleep(1);
			exec('sudo mpg321 "http://jazzlounge.ice.infomaniak.ch/jazzlounge-high.mp3?.mp3"');
			break;

			case 'France inter':
			exec('sudo pkill mpg321');
			exec('pico2wave -l fr-FR -w temp.wav "Lancement de france inter."');
			exec('sudo omxplayer -o local temp.wav');
			exec('rm temp.wav');
			sleep(1);
			exec('sudo mpg321 "http://audio.scdn.arkena.com/11008/franceinter-midfi128.mp3"');
			break;

			case 'Canut':
			exec('sudo pkill mpg321');
			exec('pico2wave -l fr-FR -w temp.wav "Lancement de Radio canuut."');
			exec('sudo omxplayer -o local temp.wav');
			exec('rm temp.wav');
			sleep(1);
			exec('sudo mpg321 "http://live.francra.org:8000/radiocanut?.mp3"');
			break;

			case 'Vol':
			exec('sudo amixer cset numid=1 -- '.$phraseadire.'');
			break;

			case 'Radio p':
			exec('sudo pkill mpg321');
			sleep(1);
			exec('pico2wave -l fr-FR -w temp.wav "Arrêt de la radio."');
			exec('sudo omxplayer -o local temp.wav');
			exec('rm temp.wav');
			break;


			case 'Hello':
			case 'Salut':
			case 'Bonjour':
			case 'bonjour':
			exec('pico2wave -l fr-FR -w temp.wav "Bonjour, comment allez vous ?"');
			exec('sudo omxplayer -o local temp.wav');
			exec('rm temp.wav');
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
			exec('pico2wave -l fr-FR -w temp.wav "Je lance la musique."');
			exec('sudo omxplayer -o local temp.wav');
			exec('rm temp.wav');
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
			exec('pico2wave -l fr-FR -w temp.wav "Toutes les lumières ont été allumées."');
			exec('sudo omxplayer -o local temp.wav');
			exec('rm temp.wav');
			break;

			case 'Eteins':
			case 'Eteindre':
			case 'Éteins':
			case 'Éteins les lumières':
			case 'Éteint les lumières':
			case 'Éteindre':
			$this->Prise->eteindretout();
			exec('pico2wave -l fr-FR -w temp.wav "Toutes les lumières ont été éteintes."');
			exec('sudo omxplayer -o local temp.wav');
			exec('rm temp.wav');
			break;

			default:
			exec('pico2wave -l fr-FR -w temp.wav "Désolé, je n\'ai pas compris."');
			exec('sudo omxplayer -o local temp.wav');
			exec('rm temp.wav');
			break;

		}
	}
	public function __construct() {
		parent::__construct();
		$this->globals = $this->datas['globals'];
	}

}
