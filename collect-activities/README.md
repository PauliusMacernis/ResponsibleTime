# How to setup on Ubuntu 20.04

Make sure the paths are correct in the following examples! e.g. your username, the path to the script, etc.

- `mkdir -p /home/paulius/.responsible-time/activities` - create a directory for storing the info on collected activities
- `sudo cp /home/paulius/dev/Responsible-time/collect-activities/collect-activities.sh /usr/bin/responsible-time-collect-activities.sh` -  copy the script from repository to bin dir
- `sudo chmod +x /usr/bin/responsible-time-collect-activities.sh` - make the script executable
- `mkdir -p ~/.config/systemd/user/` - create a dir for a unit
- `touch ~/.config/systemd/user/responsible-time-collect-activities.service` - create the unit
- `nano ~/.config/systemd/user/responsible-time-collect-activities.service` - open the unit
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
- `Ctr+X` + `Y` + `Enter` - to save the file and exit the editor
- `systemctl --user start responsible-time-collect-activities.service` - Start the daemon
- `systemctl --user status responsible-time-collect-activities.service` - To see the status
- `systemctl --user enable responsible-time-collect-activities.service` - To start at boot we enable it


# General info

Activity records are stored inside the files of the `activities` dir.  
Each filename represents a day when activity happened therefore all activities are grouped in such way.  
The content of each file is ordered by the time from the earliest to the latest 
therefore reading a file from the top to the bottom will tell the story on how activities happened over the time of a day. 
