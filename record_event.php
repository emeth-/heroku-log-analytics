<?php

error_log("sb8hit**");
if (!isset($_SERVER['PHP_AUTH_USER'])) {
error_log("sb8_1**");
    header('WWW-Authenticate: Basic realm="Heroku Logs"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Please login using basic auth';
    exit;
} else {
error_log("sb8_2**");
    if($_SERVER['PHP_AUTH_USER'] == getenv('AUTH_USER') && $_SERVER['PHP_AUTH_PW'] == getenv('AUTH_PASSWORD')) {
    error_log("sb8_3**");
        //valid credentials!
    }
    else {
    error_log("sb8_4**");
        print "Invalid username/password";
        die;
    }
}

include("config.php");
include("func.php");

/*
print json_encode(getallheaders());
{"Host":"heroku-log-analytics.herokuapp.com","Connection":"close","Logplex-Msg-Count":"5","Logplex-Frame-Id":"2FBCF744B905C29CEE1D221A698DE301","Logplex-Drain-Token":"d.ea24c4bb-4bcb-406e-b78e-36d989528f65","User-Agent":"Logplex\/v141","X-Request-Id":"09d7174c-0ba5-463b-813e-ca55c36e2d0b","X-Forwarded-For":"54.87.132.14","X-Forwarded-Proto":"https","X-Forwarded-Port":"443","Via":"1.1 vegur","Connect-Time":"0","X-Request-Start":"1583265964749","Total-Route-Time":"0"}
*/

$post_input = file_get_contents('php://input');

$x = explode(" ",$post_input);
$x = array_slice($x, 7);
$post_input = implode(" ", $x);

//Example post input from Heroku
/*
$post_input = '10.81.230.166 - - [04/Mar/2020:05:04:05 +0000] "GET /static/walkthrough/challenge5/p2.png HTTP/1.1" 404 196 "https://www.google.com/" "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36"';
*/

$results = parse_log_file($post_input);

    error_log("sb9RAW**".$post_input);
    error_log("sb9**".json_encode($results));

if($results['access_date'] && $results['browser'] && $results['browser'] != 'Crawler') {
    //error_log("sb9RAW**".$post_input);
    //error_log("sb9**".json_encode($results));
    //{"ip":"10.7.193.239","access_date":"04\/Mar\/2020","access_time":"20:02:39","access_tz":"+0000","page":"\/walkthrough\/challenge17","http_code":"404","referer":"-","user_agent":"Mozilla\/5.0(Linux;Android 5.1.1;OPPO A33 Build\/LMY47V;wv) AppleWebKit\/537.36(KHTML,link Gecko) Version\/4.0 Chrome\/42.0.2311.138 Mobile Safari\/537.36\n","browser":"Chrome","os":"Android"}

    //Track Refer
    $rez = q("UPDATE track_refer SET count=count+1 WHERE key=$1", $results['referer']) or die(error_log(pg_last_error()));
    $rows_edited = pg_affected_rows($rez);

    if(!$rows_edited) {
        q("INSERT INTO track_refer (key, count) VALUES ($1, 1)", $results['referer']) or die(error_log(pg_last_error()));
    }

    //Track Page
    $rez = q("UPDATE track_page SET count=count+1 WHERE key=$1", $results['page']) or die(error_log(pg_last_error()));
    $rows_edited = pg_affected_rows($rez);

    if(!$rows_edited) {
        q("INSERT INTO track_page (key, count) VALUES ($1, 1)", $results['page']) or die(error_log(pg_last_error()));
    }

    //Track Browser
    $rez = q("UPDATE track_browser SET count=count+1 WHERE key=$1", $results['browser']) or die(error_log(pg_last_error()));
    $rows_edited = pg_affected_rows($rez);

    if(!$rows_edited) {
        q("INSERT INTO track_browser (key, count) VALUES ($1, 1)", $results['browser']) or die(error_log(pg_last_error()));
    }

    //Track OS
    $rez = q("UPDATE track_os SET count=count+1 WHERE key=$1", $results['os']) or die(error_log(pg_last_error()));
    $rows_edited = pg_affected_rows($rez);

    if(!$rows_edited) {
        q("INSERT INTO track_os (key, count) VALUES ($1, 1)", $results['os']) or die(error_log(pg_last_error()));
    }

}

die;

?>
