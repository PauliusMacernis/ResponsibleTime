# Responsible time
If you use Slack, Jira, Toggle, and you have a person to account to (e.g. PM or client) then this tool is for you.

TODO: lists to make:
- List JIRA systems (urls, boards) and associated credentials
- List Slack workspaces and associated credentials
- Google calendar credentials (to look for the time slots, to book the meeting)
- Working hours (to schedule meetings on the right time, etc.)
- People list (email aliases, Slack info, etc.)

TODO: Scripts, bots, other beauties to develop:
- Script pulling all issues from the desired JIRA columns (by default: "In Progress", "Code review", configurable).
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
