<?php
header("Content-type: text/html; charset=utf-8");

error_reporting(E_ALL);
echo '<a href="javascript:history.back();">Return Prev Page</a><br />';
$logFile = 'D:/Web/hshweb/5.Code/1.Web/log/';
if( isset($_GET['day']) ){
    $day = $_GET['day'];
    $path = $logFile.$day;
    if( !file_exists($path) ){
        exit('Dir is Not Exists');
    }
    $logs = array();
    foreach( glob($path.'/*') as $file ){
        $fp = fopen($file,'r');
        while( $str = fgets($fp) ){
            $logs[] = htmlspecialchars($str);
        }
    }
    $logs = array_reverse($logs);
    foreach( $logs as $log ){
        echo $log."<br>\n";
    }
} else {
    $paths = array();
    foreach( glob($logFile.'*') as $path ){
        $paths[] = $path;
    }
    $paths = array_reverse($paths);
    foreach( $paths as $path ){
        $day = str_replace($logFile,'',$path);
        echo '<a href="?day='.$day.'">'.$day.'</a><br />';
    }
}
exit();