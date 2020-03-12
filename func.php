<?php

function get_os($user_agent) {

    // Make case insensitive.
    $t = strtolower($user_agent);

    // If the string *starts* with the string, strpos returns 0 (i.e., FALSE). Do a ghetto hack and start with a space.
    // "[strpos()] may return Boolean FALSE, but may also return a non-Boolean value which evaluates to FALSE."
    //        http://php.net/manual/en/function.strpos.php
    $t = " " . $t;
    // Humans / Regular Users
    if (strpos($t, 'windows') || strpos($t, 'winnt'))
        return 'Windows';

    if (strpos($t, 'android'))
        return 'Android';

    if (strpos($t, 'openbsd'))
        return 'Open BSD';

    if (strpos($t, 'sunos'))
        return 'Sun OS';

    if (strpos($t, 'cros'))
        return 'Chrome OS';

    if (strpos($t, 'linux') || strpos($t, 'x11'))
        return 'Linux';

    if (strpos($t, 'iphone') || strpos($t, 'ipad') || strpos($t, 'ipod'))
        return 'iOS';

    if (strpos($t, 'mac os x') || strpos($t, 'macppc') || strpos($t, 'macintel') || strpos($t, 'mac_powerpc') || strpos($t, 'macintosh'))
        return 'Mac';
    return "Unknown";
}

function get_browser_name($user_agent)
{
    //https://www.256kilobytes.com/content/show/1922/how-to-parse-a-user-agent-in-php-with-minimal-effort

        // Make case insensitive.
        $t = strtolower($user_agent);

        // If the string *starts* with the string, strpos returns 0 (i.e., FALSE). Do a ghetto hack and start with a space.
        // "[strpos()] may return Boolean FALSE, but may also return a non-Boolean value which evaluates to FALSE."
        //        http://php.net/manual/en/function.strpos.php
        $t = " " . $t;

        // Humans / Regular Users
        if     (strpos($t, 'opera'     ) || strpos($t, 'opr/')     ) return 'Opera'            ;
        elseif (strpos($t, 'edge'      )                           ) return 'Edge'             ;
        elseif (strpos($t, 'chrome'    )                           ) return 'Chrome'           ;
        elseif (strpos($t, 'safari'    )                           ) return 'Safari'           ;
        elseif (strpos($t, 'firefox'   )                           ) return 'Firefox'          ;
        elseif (strpos($t, 'msie'      ) || strpos($t, 'trident/7')) return 'Internet Explorer';

        // Search Engines
        elseif (strpos($t, 'google'    )                           ) return 'Crawler'   ;
        elseif (strpos($t, 'bing'      )                           ) return 'Crawler'     ;
        elseif (strpos($t, 'slurp'     )                           ) return 'Crawler';
        elseif (strpos($t, 'duckduckgo')                           ) return 'Crawler' ;
        elseif (strpos($t, 'baidu'     )                           ) return 'Crawler'       ;
        elseif (strpos($t, 'yandex'    )                           ) return 'Crawler'      ;
        elseif (strpos($t, 'sogou'     )                           ) return 'Crawler'       ;
        elseif (strpos($t, 'exabot'    )                           ) return 'Crawler'      ;
        elseif (strpos($t, 'msn'       )                           ) return 'Crawler'         ;

        // Common Tools and Bots
        elseif (strpos($t, 'mj12bot'   )                           ) return 'Crawler'     ;
        elseif (strpos($t, 'ahrefs'    )                           ) return 'Crawler'       ;
        elseif (strpos($t, 'semrush'   )                           ) return 'Crawler'      ;
        elseif (strpos($t, 'rogerbot'  ) || strpos($t, 'dotbot')   ) return 'Crawler';
        elseif (strpos($t, 'frog'      ) || strpos($t, 'screaming')) return 'Crawler';
        elseif (strpos($t, 'blex'      )                           ) return 'Crawler'       ;

        // Miscellaneous
        elseif (strpos($t, 'facebook'  )                           ) return 'Crawler'     ;
        elseif (strpos($t, 'pinterest' )                           ) return 'Crawler'    ;

        // Check for strings commonly used in bot user agents
        elseif (strpos($t, 'crawler' ) || strpos($t, 'api'    ) ||
                strpos($t, 'spider'  ) || strpos($t, 'http'   ) ||
                strpos($t, 'bot'     ) || strpos($t, 'archive') ||
                strpos($t, 'info'    ) || strpos($t, 'data'   )    ) return 'Crawler'   ;

        return 'Unknown';
}

function trigger_regex($post_input) {
    $regex = '/^(\S+) (\S+) (\S+) \[([^:]+):(\d+:\d+:\d+) ([^\]]+)\] \"(\S+) (.*?) (\S+)\" (\S+) (\S+) "([^"]*)" "([^"]*)"$/';
    preg_match($regex ,$post_input, $matches);
    return $matches;
}

function parse_log_file($post_input) {
    $matches = trigger_regex($post_input);

    if(count($matches) == 0) {
        if(substr($post_input, -1) != '"') {
            //For some reason, heroku seems to be leaving off the trailing double quote in the user agent string.
            $post_input = $post_input.'"';
            $matches = trigger_regex($post_input);
        }
    }

    $browser_name = get_browser_name($matches[13]);
    $os_name = get_os($matches[13]);
    if($browser_name == 'Crawler') {
        $os_name = 'Crawler';
    }

    $final_obj = [
        "ip" => $matches[1],
        "access_date" => $matches[4],
        "access_time" => $matches[5],
        "access_tz" => $matches[6],
        "page" => $matches[8],
        "http_code" => $matches[10],
        "referer" => $matches[12],
        "user_agent" => $matches[13],
        "browser" => $browser_name,
        "os" => $os_name
    ];
    return $final_obj;
}


if (!function_exists('getallheaders')) {
    function getallheaders() {
    $headers = [];
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
        }
    }
    return $headers;
    }
}

?>
