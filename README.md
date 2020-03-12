# heroku-log-analytics

Do you have an app on Heroku you'd like to see stats for? This app is what you want! It'll display various statistics by parsing your logs - browsers, operating systems, pages visited, and referers, all while excluding bots/crawlers from polluting the stats.

To activate this app, all you need to do is deploy it to Heroku, then follow the guide/command it presents on how to install it as a log drain on your main Heroku app you wish to have stats for.

Deploy to your own Heroku instance with this button below! After deploying, visit /post_install.php to see the command to setup the log drain.

[![Deploy](https://www.herokucdn.com/deploy/button.png)](https://heroku.com/deploy)

* Note currently only tested with Apache php logs on Heroku (it works) and Gunicorn Python/Django logs on Heroku (it doesn't work)

### TODO
- Add button to export data
- Add button to reset data
- Add support for nginx/gunicorn logs
