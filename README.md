# basic idea
Stolen from: https://blog.haschek.at/2017/how-to-defend-your-website-with-zip-bombs.html  

Detect if this 404/400-error is caused by a scanning-script, if so deliver a zip-bomb.

# extended with

- https://perishablepress.com/4g-ultimate-user-agent-blacklist/ 
- http://www.bestyoucanget.com/badua.htm 
- copy of website https://www.crashmybrowser.com 
- bit modified https://en.wikipedia.org/wiki/Billion_laughs_attack 
- inspred by SMTP greeing delay anti-spam technique
- whitelist of certain search-engines
- hope that client verifies `Content-MD5`


# known issues

- User Agent matching is only approximation, complete and correct matching of all regex would be terrible slow
- Bad-Url-List is mostly derived from my access-logs
