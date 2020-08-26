# How to setup on Ubuntu 20.04

Make sure the paths are correct in the following examples! Especially:  

- The path to the script: change `/home/paulius/dev/Responsible-time/collect-activities/collect-activities.sh` to the script you have pulled from git
- Your home directory: change `/home/paulius` to the path of yours, everywhere you see in this document

And here are the instructions on how to install and activate the script collecting activities:

- `mkdir -p ~/.responsible-time/activities` - create a directory for storing the info on collected activities
- `sudo cp /home/paulius/dev/Responsible-time/collect-activities/collect-activities.sh /usr/bin/responsible-time-collect-activities.sh` -  copy the script from repository to bin dir
- `sudo chmod +x /usr/bin/responsible-time-collect-activities.sh` - make the script executable in the new place
- `mkdir -p ~/.config/systemd/user/` - create a dir for a unit
- `touch ~/.config/systemd/user/responsible-time-collect-activities.service` - create the unit
- `nano ~/.config/systemd/user/responsible-time-collect-activities.service` - open the unit for edit
- Add the following content to the unit, make sure there are no empty spaces in front of each line:
  ```  
  [Unit]
  Description=Responsible time: Collect activities
  PartOf=graphical-session.target
  
  [Service]
  ExecStart=/usr/bin/responsible-time-collect-activities.sh /home/paulius/.responsible-time/activities
  Restart=on-failure
  
  [Install]
  WantedBy=default.target
  ```
- `Ctr+X` + `Y` + `Enter` - to save the unit file and exit the editor
- `touch ~/.config/autostart/Responsible-time-collect-activities.desktop` - create a file that will launch the unit on startup
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
- `tail -f ~/.responsible-time/activities/*` - To see the status of the data, the records must update and the titles must not be empty.


# General info

Activity records are stored inside the files of the `activities` dir.  
Each filename represents a day when activity happened therefore all activities are grouped in such way.  
The content of each file is ordered by the time from the earliest to the latest 
therefore reading a file from the top to the bottom will tell the story on how activities happened over the time of a day. 
