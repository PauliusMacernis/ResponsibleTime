#!/bin/bash

DBUS_MONITOR_EXPRESSION_PATH=/org/freedesktop/DBus
DBUS_MONITOR_INTERFACE=org.freedesktop.DBus
DBUS_MONITOR_MEMBER=AddMatch


### !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
###
### THIS IS INCOMPLETE SCRIPT. SEE ./README.md
###
### !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


# TODO: This way of fetching users may not work in case multiple users logs in and out
#  USERS_LOGGED_IN=$(who -uw | head -1)
#  IS_SCREEN_LOCKED=$(gnome-screensaver-command -q)

#  https://askubuntu.com/questions/204073/how-to-run-script-after-resume-and-after-unlocking-screen
#  IS_SCREEN_LOCKED=$("date -ud @$(getSecondsElapsed)")

#  gnome-screensaver-command -qt >>"$ACTIVITY_RECORD_FILE"-test 2>&1
#  dbus-monitor --monitor >>"$ACTIVITY_RECORD_FILE"-dbus-monitor 2>&1


# sudo cp /home/paulius/dev/ResponsibleTime/collect-activities/detect-inactivity.sh /etc/init.d/detect-inactivity.sh
# sudo chmod +x /etc/init.d/detect-inactivity.sh
# cd /etc/rc2.d
# sudo ln -s /etc/init.d/detect-inactivity.sh .


#dbus-monitor --monitor "path='$DBUS_MONITOR_EXPRESSION_PATH',interface='$DBUS_MONITOR_INTERFACE',member='$DBUS_MONITOR_MEMBER'" |
dbus-monitor --monitor |
while read -r line; do
#    echo "$line" |
      echo "$line" >> /home/paulius/.ResponsibleTime/activities/detect-inactivity.log
#      if echo "$line" | grep -q '/org/gnome/desktop/lockdown/' ; then
#          echo 'Logged off' >> /home/paulius/.ResponsibleTime/activities/detect-inactivity.log
#      fi
#    echo "$line" | grep '/org/gnome/desktop/lockdown/' >> /home/paulius/.ResponsibleTime/activities/detect-inactivity.log
#| grep ActiveChanged && your_script_goes_here
done


# ----------------------------- example from file:///etc/pm/sleep.d/ ---------------------------------------------------
#!/bin/sh
#DATE_FOR_A_RECORD=$(date --utc +%FT%T.%3NZ);
#echo "$DATE_FOR_A_RECORD" >> /home/paulius/may-remove-this-dir
#
## Action script ensure that unattended-upgrades is finished
## before a hibernate
##
## Copyright: Copyright (c) 2009 Michael Vogt
## License:   GPL-2
##
#
#PATH=/sbin:/usr/sbin:/bin:/usr/bin
#SHUTDOWN_HELPER=/usr/share/unattended-upgrades/unattended-upgrade-shutdown
#
#if [ -x /usr/bin/python3 ]; then
#    PYTHON=python3
#else
#    PYTHON=python
#fi
#
#if [ ! -x /usr/share/unattended-upgrades/unattended-upgrade-shutdown ]; then
#	exit 0
#fi
#
#case "${1}" in
#        hibernate)
#                if [ -e $SHUTDOWN_HELPER ]; then
#         	    $PYTHON $SHUTDOWN_HELPER --stop-only
#                fi
#                ;;
#        resume|thaw)
#		# nothing
#                ;;
#esac
