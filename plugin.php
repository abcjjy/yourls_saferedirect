<?php

    /*
     Plugin Name: Safe Redirect
     Plugin URI: https://github.com/abcjjy/yourls_saferedirect
     Description: 302 redirect, js redirect and manual click in one response.
     Version: 1.0
     Author: Justin Jia
     Author URI: http://github.com/abcjjy
     */

if( !defined( 'YOURLS_ABSPATH' ) ) die();
yourls_add_action('pre_redirect', 'saferd_redirect_function');

function saferd_redirect_function($args)
{
    $url   = $args[0];
    $code  = 302; //$args[1];
    $match = strpos($url, yourls_site_url(false));

    if ($match)
        return;

    header('Content-Type: text/html; charset=utf-8');

	yourls_status_header( $code );
    header( "Location: $url" );

    yourls_do_action( 'pre_redirect_javascript', $url, false);

	$url = yourls_apply_filter( 'redirect_javascript', $url, false);

    echo <<<REDIR
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<meta charset="UTF-8">
</head>
<body>
    <script type="text/javascript">
    window.location="$url";
    </script>
    <p>If you are not redirected after 3 seconds, please <a href="$url"><b>click here</b></a></p>
    <p>如果3秒内没有自动跳转, <a href="$url"><b>请点这里</b></a></p>
</body>
</html>
REDIR;

	yourls_do_action( 'post_redirect_javascript', $url );

    die();
}
?>
