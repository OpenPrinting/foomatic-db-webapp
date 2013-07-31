<?php
include('inc/siteconf.php');

$CONF = new SiteConfig();

/*
    Look for values enclosed with '@' in mysql.conf.in
    and replace them with actual data from SiteConfig
*/
$fh = fopen("mysql.conf.in", 'r');
while ($str = fgets($fh)) {
    preg_match('/\@(.+)\@/', $str, $matches);
    print str_replace("@".$matches[1]."@", $CONF->$matches[1], $str);
}

fclose($fh);

?>
