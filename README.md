# PHP Slack Bot with Simsimi api.
Example Slack bot in PHP language that connect to Simsimi api sandbox. This one support simsimi trial api. If you want to use full simsimi api, you must change endpoint config to endpoint of paid api. You can make bot with stupid talking style. LOL

# How to use
- Get slack token for bot can access slack api. Get your token here https://my.slack.com/services/new/bot.
- Get sandbox token from simsimi. Get your token here http://developer.simsimi.com.
- Open bot.php and add these token to config.
- Run `composer install` to install dependency packages.
- Run `php bot.php` to start bot.
- Invite your bot to channel and try to mention it. It will talk back to you. Have fun.

# Language to talk?
Default is English. If you want to talk bot with another language, edit config to change locale of simsimi api. View locale support at http://developer.simsimi.com/lclist.