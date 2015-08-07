<?php
require_once 'config.php';
isset($_SESSION) || session_start();
$t = isset($_SESSION['erp_verify']) ? $_SESSION['erp_verify'] : 'unknown';
echo "Num: ".$t;
echo "\n";
seccodeconvert($t);
echo "Code: ".$t;
echo "\n";
exit;
var_dump( $t );
require_once 'seccodeProxy.class.php';
var_dump( seccodeProxy::generateImg("erp_verify",$t) );