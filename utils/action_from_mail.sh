#!/bin/bash

MAILTO=""
MAILDIR=""
FROM=""
SUBJECT=""
ACTION=""


if [ "$(ls -A $MAILDIR/)" ]; then
	find $MAILDIR/ -mindepth 1 -delete
fi
python /home/pi/Downloads/rPi/gmail_fetch.py

if [ "$(ls -A $MAILDIR/)" ]; then
     FILE="$(ls -A $MAILDIR/ | head -1)"
     i=0
     while IFS='' read -r line || [[ -n "$line" ]]; do
       export VAR"$i"="$line"
       i=`echo "$i+1" | bc`
     done < "$MAILDIR/$FILE"
     if [[ "$VAR0" == *"$FROM"* && "$VAR2" == *"$SUBJECT"* && "$VAR3" == *"$ACTION"* ]]; then
	`less /var/www/html/domotique/datas/datas.json | mail -s "Rapport" $MAILTO`
     else
       echo "none action to perform"
     fi
fi
