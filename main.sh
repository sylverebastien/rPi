#!/bin/bash

flags='hi:u'
_help () {
cat <<EOF
Usage: ${0##*/} [-$flags]
-h  display this help
-i  install [all/base/mail/voice/camera/radiofz]
-u  update domotique folder in apache
EOF
}

_install () {
  if [[ $1 == "all" || $1 == "base" ]]; then
	sudo apt-get update
	sudo apt-get install -y bc
	sudo apt-get install -y apache2
	sudo apt-get install -y php5 libapache2-mod-php5
	sudo apt-get install -y curl php5-curl
	sudo apt-get install -y alsa-utils mpg123 mpg321 moc moc-ffmpeg-plugin
	sudo apt-get install -y wakeonlan
  elif [[ $1 == "all" || $1 == "mail" ]]; then
	sudo apt-get update
	sudo apt-get install -y ssmtp mailutils mpack
	sudo chfn -f "rPi" root
	mkdir -p $DIR/mail
  elif [[ $1 == "all" || $1 == "voice" ]]; then
	sudo apt-get install -y libttspico-utils
  elif [[ $1 == "all" || $1 == "camera" ]]; then
	sudo apt-get install motion
	mkdir -p $DIR/motion
  elif [[ $1 == "all" || $1 == "radiofz" ]]; then
	mkdir -p $DIR/utils
	cd $DIR/utils
	git clone git://git.drogon.net/wiringPi
	git clone https://github.com/r10r/rcswitch-pi
	git clone git://github.com/ninjablocks/433Utils.git
	cd wiringPi/
	./build
	sudo ldconfig
	cp $DIR/utils/rcswitch-pi/* $DIR/utils/433Utils/rc-switch/
	cd $DIR/utils/433Utils/RPi_utils/
	make
	sudo ln -s $DIR/utils/433Utils/RPi_utils/codesend /usr/bin/codesend
	sudo ln -s $DIR/utils/433Utils/RPi_utils/RFSniffer /usr/bin/RFSniffer
	rm -rf $DIR/utils/rcswitch-pi
  else
	sudo service apache2 restart
  fi
}

_update () {
  sudo service apache2 restart
  sudo cp -r $DIR/domotique/ /var/www/html/
  sudo chown -R www-data:www-data /var/www/html/domotique/
}

DIR="$(cd -P "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$DIR"

while getopts ":$flags" o; do
  case "${o}" in
    h)  _help
    exit;;
    i)  _install "${OPTARG}"
    exit;;
    u)  _update
    exit;;
    *)	echo "Usage: $0 [-$flags]" 1>&2; exit 1;;
  esac
done
