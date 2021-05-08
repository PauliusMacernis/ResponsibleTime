# About

This code analyzes collected activity log files based on the user requested start-end dates
and compresses the findings into durations.

## Install:
`composer install`

## Use

```
php index.php --from="2020-09-16T10:38:16.424000" --to="2020-09-16T10:38:49.290000"
```

Output:  
```
----------------------------------------------------------------------------------------------
User datetime: 
2020-09-16T10:38:16.424Z - 2020-09-16T10:38:49.290Z
----------------------------------------------------------------------------------------------

Files: 
/home/paulius/.ResponsibleTime/activities/DemoData/2020-09-16 


[2020-09-16T10:38:16.424Z - 2020-09-16T10:38:16.425Z]
2020-09-16T10:38:15.415Z 0x01c00005  0 4531   google-chrome.Google-chrome  paulius-GL503VM KRAUJASPALVĖ ŪMĖDĖ - Google Chrome


[2020-09-16T10:38:16.425Z - 2020-09-16T10:38:17.438Z]
2020-09-16T10:38:16.425Z 0x01c00005  0 4531   google-chrome.Google-chrome  paulius-GL503VM nutrition facts Bolete mushroom - Google Search - Google Chrome


[2020-09-16T10:38:17.438Z - 2020-09-16T10:38:18.470Z]
2020-09-16T10:38:17.438Z 0x01c00005  0 4531   google-chrome.Google-chrome  paulius-GL503VM nutrition facts Bolete mushroom - Google Search - Google Chrome


[2020-09-16T10:38:18.470Z - 2020-09-16T10:38:19.499Z]
2020-09-16T10:38:18.470Z 0x01c00005  0 4531   google-chrome.Google-chrome  paulius-GL503VM Natural product, Boletus Calories - Fungi - Fddb - Google Chrome


[2020-09-16T10:38:19.499Z - 2020-09-16T10:38:20.529Z]
2020-09-16T10:38:19.499Z 0x01c00005  0 4531   google-chrome.Google-chrome  paulius-GL503VM Ergothioneine - Wikipedia - Google Chrome


[2020-09-16T10:38:20.529Z - 2020-09-16T10:38:21.558Z]
2020-09-16T10:38:20.529Z 0x01c00005  0 4531   google-chrome.Google-chrome  paulius-GL503VM Inbox (368) - sugalvojau@gmail.com - Gmail - Google Chrome

... (skipping a lot of lines) ...

[2020-09-16T10:38:46.199Z - 2020-09-16T10:38:47.216Z]
2020-09-16T10:38:46.199Z 0x03400007  0 62719  org.gnome.Nautilus.Org.gnome.Nautilus  paulius-GL503VM ResponsibleTime


[2020-09-16T10:38:47.216Z - 2020-09-16T10:38:48.250Z]
2020-09-16T10:38:47.216Z 0x03400007  0 62719  org.gnome.Nautilus.Org.gnome.Nautilus  paulius-GL503VM ResponsibleTime


ResponsibleTime\Main::processRecordLast
[2020-09-16T10:38:48.250Z - 2020-09-16T10:38:49.290Z]
2020-09-16T10:38:48.250Z 0x03400007  0 62719  org.gnome.Nautilus.Org.gnome.Nautilus  paulius-GL503VM ResponsibleTime

```

## Minimum Valuable Increments

- up to 2020-05-08: Activity records collector for Ubuntu Linux based on user's interaction with GUI.
- up to 2020-05-08: Collected user activity records transformed into smallest possible durations (activity records from-to).
