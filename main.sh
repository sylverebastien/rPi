#!/bin/bash

flags='hi:u'
_help () {
cat <<EOF
Usage: ${0##*/} [-$flags]
-h  display this help
-i  install [all/base/mail/voice/camera/radiofz/sensor/snowboy]
-u  update domotique folder in apache
EOF
}

_install () {
  sudo apt-get update
  if [[ $1 == "all" || $1 == "base" ]]; then
	sudo apt-get install -y bc
	sudo apt-get install -y apache2
	sudo apt-get install -y php5 libapache2-mod-php5
	sudo apt-get install -y curl php5-curl
	sudo apt-get install -y alsa-utils mpg123 mpg321 moc moc-ffmpeg-plugin omxplayer
	sudo apt-get install -y wakeonlan
	sudo modprobe snd_bcm2835
	sudo amixer -c 0 cset numid=3 1
  fi
  if [[ $1 == "all" || $1 == "mail" ]]; then
	sudo apt-get install -y ssmtp mailutils mpack
	sudo chfn -f "rPi" root
	mkdir -p $DIR/utils/mail
  fi
  if [[ $1 == "all" || $1 == "voice" ]]; then
	sudo apt-get install -y libttspico-utils
  fi
  if [[ $1 == "all" || $1 == "camera" ]]; then
	sudo apt-get install -y motion
	sudo modprobe bcm2835-v4l2
	echo "bcm2835-v4l2" | sudo tee -a /etc/modules
	mkdir -p $DIR/utils/motion
  fi
  if [[ $1 == "all" || $1 == "radiofz" ]]; then
	mkdir -p $DIR/utils
	cd $DIR/utils
	git clone https://github.com/wiringPi/wiringPi.git
	git clone --recursive https://github.com/ninjablocks/433Utils.git
	cd wiringPi/
	./build
	sudo ldconfig
	cd $DIR/utils/433Utils/RPi_utils/
	make
	sudo ln -s $DIR/utils/433Utils/RPi_utils/codesend /usr/bin/codesend
	sudo ln -s $DIR/utils/433Utils/RPi_utils/RFSniffer /usr/bin/RFSniffer
  fi
  if [[ $1 == "all" || $1 == "sensor" ]]; then
	sudo gcc -Wall $DIR/utils/dht.c -o $DIR/utils/dht -lwiringPi -lcrypt -lpthread -lm -lrt
	sudo ln -s $DIR/utils/dht /usr/bin/dht
  fi
  if [[ $1 == "all" || $1 == "snowboy" ]]; then
	sudo apt-get install -y python-dev python-pip
	sudo pip install --upgrade pip
	export LD_LIBRARY_PATH=/usr/local/lib:$LD_LIBRARY_PATH
	sudo apt-get install -y python-pyaudio python3-pyaudio sox
	sudo apt-get install -y libatlas-base-dev libatlas-dev
	sudo pip install pyaudio
	sudo apt-get install -y swig
	cd $DIR/utils
	git clone https://github.com/Kitt-AI/snowboy.git
	cd snowboy/swig/Python && make && cd $DIR/utils
	cp snowboy/swig/Python/_snowboydetect.so .
	cp snowboy/examples/Python/snowboydecoder.py .
	sudo chmod u+x snowboyde*
	rm -rf snowboy/
  fi
  sudo service apache2 restart
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
