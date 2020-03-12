To start gathering stats, you need to run the following command using Heroku's command line interface:
<br><br>
heroku drains:add https://user:pass@<?=$_SERVER['HTTP_HOST']?>/record_event.php -a YOUR-HEROKU-APP-NAME
<br><br>
<ul>
    <li>Replace 'user' with the 'AUTH_USER' value you made up</li>
    <li>Replace 'pass' with the 'AUTH_PASSWORD' value you made up</li>
    <li>Replace YOUR-HEROKU-APP-NAME with the name of the application you wish to monitor</li>
</ul>
<br><br>
Then <a href='index.php'>click here</a> to view logs. You'll need to login with those AUTH_USER and AUTH_PASSWORD values you set.
