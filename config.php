<?

$database_url = getenv('DATABASE_URL');
if(!$database_url) {
    $database_url = "postgres://localhost:5432/htc-logs";
}

$databaseurl_pieces = parse_url($database_url);
$server = $databaseurl_pieces['host'];
$username = $databaseurl_pieces['user'];
$password = $databaseurl_pieces['pass'];
$port = $databaseurl_pieces['port'];
$db = substr($databaseurl_pieces['path'], 1);

// Connecting, selecting database
$db_connection_string = "host=".$server." dbname=".$db;
if($username) {
    $db_connection_string .= " user=".$username;
}
if($password) {
    $db_connection_string .= " password=".$password;
}
if($port) {
    $db_connection_string .= " port=".$port;
}
$c = pg_connect($db_connection_string) or die('Could not connect: ' . pg_last_error());

function q($query) {
    global $c;
    /*
    // non-parameterized example
    q($c, "SELECT $val1 + $val2");
    // parameterized example
    q($c, "SELECT $1 + $2", $val1, $val2);
    */
    if(func_num_args() == 1){
        return pg_query($c, $query);
    }

    $args = func_get_args();
    $params = array_splice($args, 1);
    return pg_query_params($c, $query, $params);
}

function debug_q($sql) {
    //Parameters are passed in as function parameters, not an array

    $args = func_get_args();
    $params = array_splice($args, 1);

    $debug = preg_replace_callback(
            '/\$(\d+)\b/',
            function($match) use ($params) {
                $key=($match[1]-1); return ( is_null($params[$key])?'NULL':pg_escape_literal($params[$key]) );
            },
            $sql);

    echo "$debug";
}

?>
