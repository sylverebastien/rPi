<?php
class GladysModel extends AppModel {


	public function direPhrase($phrase) {
		echo "$phrase";
		`pico2wave -l fr-FR -w temp.wav "$phrase"`;
		exec('sudo omxplayer -o local temp.wav');
		exec('rm temp.wav');
	}

	public function respond($tothisphrase) {
		$tothisphrase = ucfirst($tothisphrase);

		if ( stripos($tothisphrase, 'Dis') !== false && stripos($tothisphrase, 'dis') == 0 ) {
			$phraseadire = str_replace('Dis', '', $tothisphrase);
			$tothisphrase = 'Dis';
		}
		if ( stripos($tothisphrase, 'Exec') !== false && stripos($tothisphrase, 'Exec') == 0 ) {
			$phraseadire = str_replace('Exec ', '', $tothisphrase);
			$tothisphrase = 'Exec';
		}
		if ( stripos($tothisphrase, 'Vol') !== false && stripos($tothisphrase, 'Vol') == 0 ) {
			$phraseadire = str_replace('Vol ', '', $tothisphrase);
			$tothisphrase = 'Vol';
		}

		switch($tothisphrase){

			case 'Radio':
			exec('sudo pkill mpg321');
			$this->direPhrase('Nova, Brume, Jazz, France inter, Canut. Radio p pour arrêter.');
			break;

			case 'Nova':
			exec('sudo pkill mpg321');
			$this->direPhrase('Lancement de Radio Nova.');
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
			$this->direPhrase('Lancement de France Inter.');
			sleep(1);
			exec('sudo mpg321 "http://audio.scdn.arkena.com/11008/franceinter-midfi128.mp3"');
			break;

			case 'Canut':
			exec('sudo pkill mpg321');
			$this->direPhrase('Lancement de Radio Canut.');
			sleep(1);
			exec('sudo mpg321 "http://live.francra.org:8000/radiocanut?.mp3"');
			break;

			case 'Vol':
			exec('sudo amixer cset numid=1 -- '.$phraseadire.'');
			break;

			case 'Radio p':
			$this->Music = new MusicModel();
			$this->Music->pause();
			exec('sudo pkill mpg321');
			break;

			case 'Hello':
			case 'Salut':
			case 'Bonjour':
			$this->direPhrase('Bonjour, comment allez vous ?');
			break;

			case 'Exec':
			echo exec($phraseadire);
			break;

			case 'Dis':
			$this->direPhrase($phraseadire);
			break;

			case 'Play':
			case 'Musique':
			$this->direPhrase('Je lance la musique.');
			sleep(1);
			$this->Music = new MusicModel();
			$this->Music->play();
			break;

			case 'Stop':
			case 'Pause':
			$this->Music = new MusicModel();
			$this->Music->pause();
			exec('sudo pkill mpg321');
			break;

			case 'Precedent':
			case 'Précédent':
			case 'Previous':
			case 'Last':
			$this->Music = new MusicModel();
			$this->Music->precedent();
			break;

			case 'Suivant':
			case 'Next':
			$this->Music = new MusicModel();
			$this->Music->suivant();
			break;

			case 'Lumières on':
			case 'Lumieres on':
			$this->Prise->allumertout();
			$this->direPhrase('Toutes les lumières ont été allumées.');
			break;

			case 'Lumières off':
			case 'Lumieres off':
			$this->Prise->eteindretout();
			$this->direPhrase('Toutes les lumières ont été éteintes.');
			break;

			case 'Meteo':
			case 'Météo':
			$this->Utils = new UtilsModel();
			$this->Utils->meteo();
			break;

			case 'Date':
			$this->Utils = new UtilsModel();
			$this->Utils->date();
			break;

			default:
			$this->direPhrase('Désolé, je n\'ai pas compris.');
			break;

		}
	}

}
