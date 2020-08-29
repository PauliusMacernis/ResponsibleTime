# Responsible time
If you use Slack (https://slack.com/), Jira (https://www.atlassian.com/software/jira), Toggl (https://toggl.com/), Float (https://www.float.com/), Todoist (https://todoist.com/) and you have a person to account to (e.g. PM or client) then this tool is for you.

THE LIST IS ON ARCHITECTURING PHASE. THEREFORE IT MAY CHANGE A BIT. TRYING TO FIND THE BEST SOLUTION "ON THE PAPER" AT FIRST.


TODO: Scripts, bots, other beauties to develop:
- Scan working processes for "task-alike" (e.g. "AP-number") patterns.  
  See: https://superuser.com/questions/382616/detecting-currently-active-window  
  Explore: 
  ```
  for x in $(seq 1 10); do sleep 5; wmctrl -lp | grep $(xprop -root | \
  grep _NET_ACTIVE_WINDOW | head -1 | awk '{print $5}' | sed 's/,//' | \
  sed 's/^0x/0x0/'); done
  ```
  - Catch when the screen is locked
  - Catch when no activity (no keyboard click, no mouse click, no mouse pointer move) 
  - Determine patterns for: "work-related activity starts", "private-related activity starts". The default is "continue", if the first record - "private-related activity starts".
- --The content down bellow is under the architecture question--
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
- WH and other bonuses


TODO: lists to make:
- List JIRA systems (urls, boards) and associated credentials also "TODO", "DOING" and "DONE" mapping (all columns)
- List JIRA assignee mapping when status change, e.g. Change "In Progress" to "Code review" will change the assignee too.
- List Slack workspaces and associated credentials
- Google calendar credentials (to look for the time slots, to book the meeting)
- Working hours (to schedule meetings on the right time, etc.)
- People list (email aliases, Slack info, etc.)


# Run the project (Dev)

- `docker-compose up --build`  
- http://localhost:8088/index.php  
