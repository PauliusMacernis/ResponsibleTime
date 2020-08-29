# General info

Activity records are stored inside the text files of the `activities` dir.  
Each filename represents a day when activity happened therefore all activities are grouped in such way.  
The content of each file is ordered by the time from the earliest to the latest 
therefore reading a file from the top to the bottom will tell the story on how activities happened over the time of a day. 


# How to setup on Ubuntu 20.04

Make sure the paths are correct in the following examples! Especially:  

- The path to the script: change `/home/paulius/dev/Responsible-time/collect-activities/collect-activities.sh` to the path of the script with the same name you have pulled from git
- Your home directory: change `/home/paulius` to the path of yours, everywhere you see in this document

## Collect activities

Collects info on what you do and logs that info to the directory on your machine.
And here are instructions on how to install and activate the script collecting activities:

- `mkdir -p ~/.responsible-time/activities` - create a directory for storing records on your activities
- `sudo cp /home/paulius/dev/Responsible-time/collect-activities/collect-activities.sh /usr/bin/responsible-time-collect-activities.sh` -  copy the script from repository to /usr/bin dir
- `sudo chmod +x /usr/bin/responsible-time-collect-activities.sh` - make the script executable in the new place.
- `mkdir -p ~/.config/systemd/user/` - create a dir for a unit
- `touch ~/.config/systemd/user/responsible-time-collect-activities.service` - create the unit
- `nano ~/.config/systemd/user/responsible-time-collect-activities.service` - open the unit for edit
- Add the following content to the systemd unit, make sure there are no empty spaces in front of each line:
  ```  
  [Unit]
  Description=Responsible time: Collect activities
  PartOf=graphical-session.target
  
  [Service]
  ExecStart=/usr/bin/responsible-time-collect-activities.sh /home/paulius/.responsible-time/activities
  # PrivateNetwork=yes
  # PrivateTmp=yes
  # ProtectSystem=full
  # ProtectHome=yes
  # PrivateDevices=yes
  Restart=on-failure
  
  [Install]
  WantedBy=default.target
  ```
- `Ctr+X` + `Y` + `Enter` - to save the unit file and exit the editor
- `touch ~/.config/autostart/Responsible-time-collect-activities.desktop` - create the file that will launch the unit on startup
- `nano ~/.config/autostart/Responsible-time-collect-activities.desktop` - open the unit launcher file for edit
- Add the following content to the launcher file:
  ```
  [Desktop Entry]
  Type=Application
  Name=Responsible time: Collect activities!
  Exec=systemctl --user start responsible-time-collect-activities.service
  Comment=Starts Responsible time. The step of collecting activities will be enabled.
  X-GNOME-Autostart-enabled=true
  ```
- `Ctr+X` + `Y` + `Enter` - to save the launcher file and exit the editor
- Restart
- `systemctl --user status responsible-time-collect-activities.service` - To see the status of the process, it must be running. 
- `tail -f ~/.responsible-time/activities/*` - To see the status of the data, the records must update, and the titles must not be empty.

## Collect inactivities

This script collects the records of inactivity. For example: the screen is locked, the system is hibernated, etc.
It is a separate script due to the nature of OS because you have to listen for specific events in order to know that something meaning inactivity happened on the OS scale.

**The alternative to the following script is the pre-step of minimizing all applications using UI (`Super` + `D`) 
and then applying the OS-related action, e.g. locking the screen without UI of applications going back. 
It may still be the issue (probably) if the application pops up due to cron or so while the session is locked.**
**THIS SCRIPT IS UNDER DEVELOPMENT - IT DOES NOT WORK WELL NOW**

- `touch ~/.config/autostart/Responsible-time-collect-inactivities.desktop` - create a launcher for inactivities detection
- `nano ~/.config/autostart/Responsible-time-collect-inactivities.desktop` - open a launcher for inactivities detection for edit
- Add the following content to the launcher file:
  ```
  [Desktop Entry]
  Type=Application
  Name=Responsible time: Collect inactivities
  Exec=systemctl --user start responsible-time-collect-inactivities.service
  Comment=Starts Responsible time. Inactivity detection will be enabled.
  X-GNOME-Autostart-enabled=true
  ```
- `Ctr+X` + `Y` + `Enter` - to save the launcher file and exit the editor

# Related info 

## Commands, related

- `sudo cp /home/paulius/dev/Responsible-time/collect-activities/collect-activities.sh /usr/bin/responsible-time-collect-activities.sh && sudo chmod +x /usr/bin/responsible-time-collect-activities.sh && systemctl --user restart responsible-time-collect-activities.service` - to restart the script after any changes
- `echo $XDG_CURRENT_DESKTOP` - to see which desktop environment is in use (expected `ubuntu:GNOME`, see for more: https://www.gnome.org/ )
- `systemctl -t service` - list available systemd services
- `systemctl list-unit-files -t service` - list all systemd unit files, including disabled ones
- `systemctl --user daemon-reload` - to reload the daemon after any changes

# Readings, related

- How screen lock works on Ubuntu: https://wiki.ubuntu.com/DebuggingScreenLocking/HowScreenLockingWorks
- system.d.unit configuration: https://www.freedesktop.org/software/systemd/man/systemd.unit.html

# Concepts, questionably related

When **locking**, not confirmed, caught the event once, cannot reproduce and so it does not work later on:  
```
method call time=1598557130.952279 sender=:1.7931 -> destination=org.freedesktop.DBus serial=13 path=/org/freedesktop/DBus; interface=org.freedesktop.DBus; member=AddMatch
   string "type='signal',interface='ca.desrt.dconf.Writer',path='/ca/desrt/dconf/Writer/user',arg0path='/org/gnome/desktop/lockdown/'"
```

Some people refer to another event record as user adding the lock, cannot reproduce it, nor it works on my machine:  
```
method call time=1512800651.865886 sender=:1.7 -> destination=:1.33 serial=1326 path=/org/gnome/ScreenSaver; interface=org.gnome.ScreenSaver; member=Lock
``` 
  
When **unlocking**, not confirmed, caught the event once, cannot reproduce and so it does not work later on:   
```
method call time=1598557137.233549 sender=:1.7931 -> destination=org.freedesktop.DBus serial=109 path=/org/freedesktop/DBus; interface=org.freedesktop.DBus; member=RemoveMatch
   string "type='signal',interface='ca.desrt.dconf.Writer',path='/ca/desrt/dconf/Writer/user',arg0path='/org/gnome/desktop/lockdown/'"
```
  
Monitor DBus events of a particular path, interface, member:  
Example #1:  
`dbus-monitor --session "path='/org/freedesktop/DBus',interface='org.freedesktop.DBus',member='AddMatch'"`    
Example #2:  
`dbus-monitor --session "path='/org/gnome/ScreenSaver',interface='org.gnome.ScreenSaver',member='Lock'"`

The following two examples work, but these produce no results as no events caught with such watch expression.  
