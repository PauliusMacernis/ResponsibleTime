#!/bin/sh

DIR_FOR_ACTIVITY_RECORDS=$1

while true
do

  # See: datetime format in compress-activities/src/Settings.php - must be the same
  # ISO8601 UTC timestamp + ms
  DATE_FOR_A_RECORD=$(date --utc +%FT%T.%3NZ);
  DATE_FOR_FILENAME=$(echo "$DATE_FOR_A_RECORD" | awk '{print substr($0,0,10)}')

  ACTIVITY_RECORD_FILE=$DIR_FOR_ACTIVITY_RECORDS/$DATE_FOR_FILENAME

  # See: https://superuser.com/questions/382616/detecting-currently-active-window (the script)
  # See: https://unix.stackexchange.com/questions/85244/setting-display-in-systemd-service-file (the way to launch it)
  ACTIVITY_RECORD_TITLE=$(wmctrl -xlp | \
    grep "$(xprop -root | \
      grep _NET_ACTIVE_WINDOW | \
      head -1 | \
      awk '{print $5}' | \
      sed 's/,//' | \
      sed 's/^0x/0x0/')")

  echo "$DATE_FOR_A_RECORD"  "$ACTIVITY_RECORD_TITLE" >> "$ACTIVITY_RECORD_FILE"

  sleep 1

done
