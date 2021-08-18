The information down bellow is not accurate, nor up-to date. Check `README.md` files in subdirectories to find out more.

# Responsible time
Collects computer UI activities of yours into YOUR computer (Only Ubuntu OS is supported for now),  
compresses the collected activities into summaries ready to be copy > paste to anywhere YOU wish.  


## What's done

- *For Ubuntu 20.04 users only:* Scan daily activities, write records to the log file. See `collect-activities`
- *For Ubuntu 20.04 users only:* Inactivity not always properly detected (e.g. locking the screen) therefore minimizing all the Windows (`Super` + `D`) before is a workaround a person should be doing manually.
- *For Ubuntu 20.04 users only:* Some more advanced cases not tested, e.g.: multiple users, multiple working spaces, hibernate, etc. 
- Activities may "compress" into "activity sprints" which is same activity repeatedly recorded for seconds, minutes, etc. See `compress-activities`
- First activity starting 00:00:00 and lasting up to the real first activity in the daily record log is added as "inactivity" in case the first activity in the log is far away from the early midnight
- First activity starting 00:00:00 and lasting up to the real first activity in the daily record log is added as extended up to exact midnight (00:00:00) in case the first activity in the log is very close to the early midnight
- End time has been adjustment to 23:59:59 in the similar way - the start time has been adjusted to 00:00:00.


## Drafting the future, related ideas

THE FOLLOWING LIST IS ON ARCHITECTURE-DEVELOPMENT PHASE. THEREFORE, IT MAY CHANGE QUITE A BIT.  
I AM TRYING TO FIND THE TECHNICAL EXPLANATIONS TO "THE VISION" I HAVE, AND I TRY TO PUT ALL OF IT HERE AS "AN ABSTRACT PLAN".
  
TODO: Scripts, bots, other beauties to develop:
- Scan working processes for "task-alike" (e.g. "AP-number") patterns.  
- Catch when the screen is locked
- Catch when no activity (no keyboard click, no mouse click, no mouse pointer move) 
- Determine patterns for: "work-related activity starts", "private-related activity starts". The default is "continue", if the first record - "private-related activity starts".
- --The content down bellow is under the even more questionable state--
- When the new pattern match detected - send info to Toggle saying "stop all what is going on, start this one".
- Send "stop" to Toggle when a computer turns off, reboots, a user logs off.
- Send "start on unknown" to Toggle when certain software (patterns) starts and the clock is not running yet.
- Add "worked on unknown" time to the first "task-alike" time following.
- Optional setting: Round up the today's Toggl time to 15 minutes (configurable) at 23:59 of each day.
- I use my personal Todoist account to manage my work and life activities. It's the best: fast, easy, simple.
  - Consider Todoist alternatives:
      - TXT file on a computer (basic linux approach - minimum valuable increment, e.g. git works this way) 
      - Google calendar (this actually is the best UI tool in my point of view, timeboxing, day planning, etc. matter.)
      - Google sheets (may be easily done)
- I have a Project there called "Work" (configurable) I switch to that tab to manage my worktime-related activities.
- Issues with me as Assignee syncs to "Work" project from All JIRA projects I have set. 
- Issues are divided into subprojects, according to columns in Jira (all projects merged, may be tagged with project name).
- Issues are listed in the form of: \[{original estimate}\] {TASK-NUMBER} {Title (20 firstchars)} {link to the issue}.
- \[{original estimate}\] part is the estimate of me, not others. Therefore I can estimate my tasks and this do not conflict.
- Setting the tag of "estimate" on JIRA ticket would send messages to the developers of the project asking for the story point value written back into the same thread. This is how \[{original estimate}\] appears on request.
- You may anytime give (but not change, this is important) the value of \[{original estimate}\] manually by editing the line in Todoist. 
- If I move the issue to another subproject - issue's column is changing in JIRA too, assignee is changed by the mapping.
- I create the new ticket by writting something like: "AP> This is the task..." ("AP" stands for the project code, then txt)
- There is one special subproject called "Mental stack".
- If I move any issue to "Mental stack" it starts the Toggle clock on it.
- It may be several tasks in the "Mental stack" (multitasking). Then toggl is ticking in 1 minute (config) chunks to each.
- Order in the "Mental stack" matters. The higher is the task, the bigger time chunk (seconds) it logs to Toggl.
- The ordered list of the "Mental stack" goes to Slack as a personal status message.
- In case you have an empty "Mental stack", the message comes into Slack telling top 5 tasks with links to each you should take on by priority order.
- You have to remove task from "Mental stack" when you finish your work. Otherwise, toggl will continue.
- You will get the reminder to slack to release your "Mental stack" every 2 hours (config) (in case >1 issue there).
- Script X pulls all issues from the desired JIRA columns (by default: "DOING": "In Progress", "Code review", configurable).
- Fetches an Assignee and watchers of each task, retrieves  emails of each individual from JIRA. 
- Merges the email addresses got from JIRA with "email aliases" set in the system, in case more exists.
- The script finds the Assignee by email in Slack
- The script sends Assignee the message to Slack "How many hours left to work on: {link to JIRA task}?"
- The former question is sent only between 45 to 59 minutes of any hour. These are "prefered communication hours" matching law.
- User responds by writing the number (float) to the new thread, in case more links are posted at once.
- The number is being taken by the script and sent to the related JIRA issue to the "Time remaining" field to be updated.
- Each JIRA task has it's evaluation in hours (so far in hours, "story points" field).
- PM sets "the level of tolerance" for each JIRA board, e.g. 20%
- The script monitors JIRA tasks to see if any has reached the level of 100 + tolerance% (max level tolerated) in duration
- If such max level detected then the script finds the email of assignee and the reporter (which suppose to be PM)
- Then script looks at the Google calendar of both and books the meeting of 15 minutes in the closest working time possible
- Then the script takes the google hangouts link and posts that to the slack for both together with the link to the issue
- Another script syncs data from Toggle to Jira and adds the time spent according to the records in Toggl.
- Adding extra callendar items or alternative (@todo: think) according to records in the Float system.
- Post calendar event to JIRA ticket, e.g. as a comment with the info of the past event, e.g. description, time, etc.
- Statistics (story points / hour)
- Adding Comptia troubleshooting steps as subtasks to each bug-type task: https://www.comptia.org/content/guides/a-guide-to-network-troubleshooting
- Track TODO items in the code of the project and use it in metrics too, e.g. to determine how TODO items impact hours/SP ratio.  
- Track vacation time collected. Send a private Slack message each time one more day counts in or out.
- Count time zones in, work by traveling.
- Multiuser support on one machine?
- Collect "TODO" out of each project and make it count as "technical debt & investment" impacts the estimation, health, etc.
- WH and other bonuses, e.g. switch times between activities correlation to WH?
- May Screenshots every now and then + Computer Vision be used to detect inactivity? E.g. something like the following code:

```
#!/usr/bin/env bash
while true
do 
 scrot '%Y-%m-%d-%H:%M:%S.png' -e 'mv $f ~/Pictures/RegularScreenshots/' 
 fswebcam -r 1280x720 --jpeg 85 -D 1 "$HOME/Pictures/RegularWebcam/%Y-%m-%d-%H:%M:%S.jpg"
 sleep 300
done
```

It seems the required lists to make may be these:
- List JIRA systems (urls, boards) and associated credentials also "TODO", "DOING" and "DONE" mapping (all columns)
- List JIRA assignee mapping when status change, e.g. Change "In Progress" to "Code review" will change the assignee too.
- List Slack workspaces and associated credentials
- Google calendar credentials (to look for the time slots, to book the meeting)
- Working hours (to schedule meetings on the right time, etc.)
- People list (email aliases, Slack info, etc.)
