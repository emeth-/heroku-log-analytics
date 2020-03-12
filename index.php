<?php

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="Heroku Logs"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Please login using basic auth';
    exit;
} else {
    if($_SERVER['PHP_AUTH_USER'] == getenv('AUTH_USER') && $_SERVER['PHP_AUTH_PW'] == getenv('AUTH_PASSWORD')) {
        //valid credentials!
    }
    else {
        print "Invalid username/password";
        die;
    }
}

include("config.php");
include("func.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Heroku Logs</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>

  <script>

$( document ).ready(function() {
});

  </script>

  </head>

  <body>


<div class="header-div d-flex flex-row flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
  <h5 class="my-0 mr-md-auto font-weight-normal"><a href='index.php'>Heroku Logs</a></h5>
</div>

<center>
<div class="row mb-3">
    <div class="col-sm-6 col-lg-6 themed-grid-col">
<h1>Browser</h1>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Key</th>
              <th scope="col">Count</th>
            </tr>
          </thead>
          <tbody>
              <?php
        $all_records = q("SELECT * FROM track_browser ORDER BY count desc");
        while($r = pg_fetch_assoc($all_records)) {
            ?>
<tr>
<td><?=$r['key']?></td>
<td><?=number_format($r['count'])?></td>
</tr>
            <?
        }
              ?>
          </tbody>
        </table>
    </div>
    <div class="col-6 col-lg-6 themed-grid-col">
<h1>OS</h1>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Key</th>
              <th scope="col">Count</th>
            </tr>
          </thead>
          <tbody>
              <?php
        $all_records = q("SELECT * FROM track_os ORDER BY count desc");
        while($r = pg_fetch_assoc($all_records)) {
            ?>
<tr>
<td><?=$r['key']?></td>
<td><?=number_format($r['count'])?></td>
</tr>
            <?
        }
              ?>
          </tbody>
        </table>
    </div>
</div>
<hr>

<div class="row mb-3">
    <div class="col-sm-6 col-lg-6 themed-grid-col">
<h1>Page</h1>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Key</th>
              <th scope="col">Count</th>
            </tr>
          </thead>
          <tbody>
              <?php
        $all_records = q("SELECT * FROM track_page ORDER BY count desc");
        while($r = pg_fetch_assoc($all_records)) {
            ?>
<tr>
<td><?=$r['key']?></td>
<td><?=number_format($r['count'])?></td>
</tr>
            <?
        }
              ?>
          </tbody>
        </table>
    </div>
</div>
<hr>
<div class="row mb-3">
    <div class="col-6 col-lg-6 themed-grid-col">
<h1>Referer</h1>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Key</th>
              <th scope="col">Count</th>
            </tr>
          </thead>
          <tbody>
              <?php
        $all_records = q("SELECT * FROM track_refer ORDER BY count desc");
        while($r = pg_fetch_assoc($all_records)) {
            ?>
<tr>
<td><?=$r['key']?></td>
<td><?=number_format($r['count'])?></td>
</tr>
            <?
        }
              ?>
          </tbody>
        </table>
    </div>
</div>


</center>
  </body>
</html>
