# Responsible time
If you use Slack, Jira, Toggle, Todoist and you have a person to account to (e.g. PM or client) then this tool is for you.

THE LIST IS ON ARCHITECTURING PHASE. THEREFORE IT MAY CHANGE A BIT. TRYING TO FIND THE BEST SOLUTION "ON THE PAPER" AT FIRST.

TODO: lists to make:
- List JIRA systems (urls, boards) and associated credentials also "TODO", "DOING" and "DONE" mapping (all columns)
- List JIRA assignee mapping when status change, e.g. Change "In Progress" to "Code review" will change the assignee too.
- List Slack workspaces and associated credentials
- Google calendar credentials (to look for the time slots, to book the meeting)
- Working hours (to schedule meetings on the right time, etc.)
- People list (email aliases, Slack info, etc.)

TODO: Scripts, bots, other beauties to develop:
- I use my personal Todoist ( https://todoist.com/ ) account to manage my work and life activities. It's the best.
- I have a Project there called "Work" (configurable) I switch to that tab to manage my worktime-related activities.
- Issues with me as Assignee syncs to "Work" project from All JIRA projects I have set. 
- Issues are divided into subprojects, according to columns in Jira (all projects merged, may be tagged with project name).
- Issues are listed in the form of: \[{original estimate}\] {TASK-NUMBER} {Title (20 firstchars)} {link to the issue}.
- If I move the issue to another subproject - issue's column is changing in JIRA too, assignee is changed by the mapping.
- There is one special subproject called "Mental stack".
- If I move any issue to "Mental stack" it starts the Toggle clock on it.
- It may be several tasks in the "Mental stack" (multitasking). Then toggl is ticking in 1 minute (config) chunks to each.
- Order in the "Mental stack" matters. The higher is the task, the bigger time chunk (seconds) it logs to Toggl.
- "Mental stack" list in th egiven order also goes to Slack as a personal status message.
- You have to remove task from "Mental stack" when you finish your work. Otherwise, toggl will continue.
- You will get the reminder to slack to release your "Mental stack" every 2 hours (config) (in case >1 issue there).
- Script X pulls all issues from the desired JIRA columns (by default: "DOING": "In Progress", "Code review", configurable).
- Fetches an Assignee and watchers of each task, retrieves  emails of each individual from JIRA. 
- Merges the email addresses got from JIRA with "email aliases" set in the system, in case more exists.
- The script finds the Assignee by email in Slack
- The script sends Assignee the message to Slack "How many hours left to work on: {link to JIRA task}?"
- User responds by writing the number (float) to the new thread, in case more links are posted at once.
- The number is being taken by the script and sent to the related JIRA issue to the "Time remaining" field to be updated.
- Each JIRA task has it's evaluation in hours (so far in hours, "story points" field).
- PM sets "the level of tolerance" for each JIRA board, e.g. 20%
- The script monitors JIRA tasks to see if any has reached the level of 100 + tolerance% (max level tolerated) in duration
- If such max level detected then the script finds the email of assignee and the reporter (which suppose to be PM)
- Then script looks at the Google calendar of both and books the meeting of 15 minutes in the closest working time possible
- Then the script takes the google hangouts link and posts that to the slack for both together with the link to the issue
- Another script syncs data from Toggle to Jira and adds the time spent according to the records in Toggl.
- Statistics (story points / hour)
- WH and other bonuses
