<?php
class MusicModel {

	public function play() {
		exec('sudo mocp -S');
		exec('sudo mocp -c -a /var/www/html/domotique/app/assets/music -t shuffle');
		exec('sudo mocp -p');
	}
	
	public function pause() {
		exec('sudo mocp -x');
		exec('sudo pkill mpg321');
	}

	public function suivant() {
		exec('sudo mocp -f');
	}

	public function precedent() {
		exec('sudo mocp -r');
	}
}
?>
