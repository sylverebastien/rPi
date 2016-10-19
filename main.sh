#!/bin/bash

flags='hiu'
_help () {
cat <<EOF
Usage: ${0##*/} [-$flags]
-h  display this help
-i  install
-u  update domotique folder in apache
EOF
}

_install () {
  sudo apt-get update
  sudo apt-get -y upgrade
  sudo apt-get -y dist-upgrade
  sudo apt-get -y autoremove

  sudo apt-get install -y bc

  sudo apt-get install -y apache2
  sudo apt-get install -y php5 libapache2-mod-php5
  sudo apt-get install -y curl php5-curl
  sudo apt-get install -y alsa-utils mpg123 mpg321 moc moc-ffmpeg-plugin
  sudo apt-get install -y wakeonlan

  sudo apt-get install -y ssmtp mailutils mpack
  sudo chfn -f "rPi" root

  sudo apt-get install -y libttspico-utils

  sudo apt-get install motion

  mkdir -p $DIR/Downloads
  cd $DIR/Downloads/
  git clone git://git.drogon.net/wiringPi
  git clone https://github.com/r10r/rcswitch-pi
  git clone git://github.com/ninjablocks/433Utils.git

  cd wiringPi/
  ./build
  sudo ldconfig

  cp $DIR/Downloads/rcswitch-pi/* $DIR/Downloads/433Utils/rc-switch/
  cd $DIR/Downloads/433Utils/RPi_utils/
  make
  sudo ln -s $DIR/Downloads/433Utils/RPi_utils/codesend /usr/bin/codesend
  rm -rf $DIR/Downloads/rcswitch-pi

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
    i)  _wizard
    exit;;
    u)  _update
    exit;;
    *)	echo "Usage: $0 [-$flags]" 1>&2; exit 1;;
  esac
done
