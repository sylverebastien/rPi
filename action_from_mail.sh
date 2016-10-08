#!/bin/bash

MAILTO=""
MAILDIR=""
FROM=""
SUBJECT=""
ACTION=""


if [ "$(ls -A $MAILDIR/)" ]; then
  `echo rm "$MAILDIR/*"`
  python gmail_fetch.py
else
  echo "$MAILDIR is Empty"
fi

if [ "$(ls -A $MAILDIR/)" ]; then
     FILE="$(ls -A $MAILDIR/ | head -1)"
     i=0
     while IFS='' read -r line || [[ -n "$line" ]]; do
       export VAR"$i"="$line"
       i=`echo "$i+1" | bc`
     done < "$MAILDIR/$FILE"
     echo "$VAR2"
     if [[ "$VAR0" == *"$FROM"* && "$VAR2" == *"$SUBJECT"* && "$VAR3" == *"$ACTION"* ]]; then
       less /var/www/html/domotique/datas/datas.json | mail -s "Rapport" "$MAILTO"
     else
       echo "none action to perform"
     fi
fi
