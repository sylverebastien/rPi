<?php

// Adresse Mac du PC à réveiller
define('ADRESSEMAC','XX:XX:XX:XX:XX:XX');
// Ip du PC à réveiller
define('IPPC','192.168.X.X');

function iscurlinstalled() {
	if  (in_array  ('curl', get_loaded_extensions())) {
		return true;
	}
	else{
		return false;
	}
}

if(!iscurlinstalled()){ die("CURL IS MISSING"); }

?>
