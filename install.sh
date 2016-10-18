#!/bin/bash


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

cd ~/Downloads/
git clone git://git.drogon.net/wiringPi
git clone https://github.com/r10r/rcswitch-pi
git clone git://github.com/ninjablocks/433Utils.git

cd ~/Downloads/wiringPi/
./build
sudo ldconfig

cp ~/Downloads/rcswitch-pi/* ~/Downloads/433Utils/rc-switch/
cd ~/Downloads/433Utils/RPi_utils/
make
sudo ln -s ~/Downloads/433Utils/RPi_utils/codesend /usr/bin/codesend
rm -rf ~/Downloads/rcswitch-pi

sudo service apache2 restart
