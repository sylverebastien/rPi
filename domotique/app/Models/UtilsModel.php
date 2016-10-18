<?php
class UtilsModel {
	protected $city="";
	protected $country="";

	public function meteo(){
		// Récupération météo
		$url="http://api.openweathermap.org/data/2.5/weather?q=".$this->city.",".$this->country."&units=metric&appid=698179e3edfd63515ee22047a4e8de5f";
		$json=file_get_contents($url);
		$data=json_decode($json,true);
		$data['main']['temp']= explode(".", $data['main']['temp']);
		$data['main']['humidity']= explode(".", $data['main']['humidity']);
		$data['wind']['speed']= explode(".", $data['wind']['speed']);
		// Taduction météo
		$mot = array("Clear","Clouds","Rain");
		$mot2 = array("Clair","nuageux","Pluvieux");
		$data['weather'][0]['main'] = str_replace($mot, $mot2, $data['weather'][0]['main']);
		// Phrases pour TTS
		$text ='Il fait '.$data['main']['temp'][0].' degrés, et le temps est '.$data['weather'][0]['main'].'.';
		$text2 ='Il y a '.$data['main']['humidity'][0].' % d\'humidité et le vent souffle à '.$data['wind']['speed'][0].' mètres par seconde.';
		$weekend = strftime('%A');
		// TTS
		$this->direPhrase($text);
		$this->direPhrase($text2);
	}

	public function date(){
		$date = strftime('Nous sommes le %A %d %B %Y, et il est %H:%M');
		$healthy = array("Monday", "Tuesday", "Wednesday","Thursday","Friday","Saturday","Sunday","January","February","March","April","May","June","July","August","September","October","November","December");
		$yummy   = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi","Samedi","Dimanche","Janvier","Fevrier","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre","Octobre","Novembre","Décembre");
		$date = str_replace($healthy, $yummy, $date);
		$this->direPhrase($date);
	}

	public function alarme($val){
		if ($val == "1") { exec('sudo motion'); }
		else { exec('sudo pkill motion'); }
	}
}
