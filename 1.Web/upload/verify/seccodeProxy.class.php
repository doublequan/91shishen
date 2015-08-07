<?php

class seccodeProxy {
    public static function generateImg( $verifyName ) {
        require_once 'config.php';

        $_SESSION[$verifyName] = $seccode = random(6, 1);
        //check referer
        /**
        var_dump($_SERVER['HTTP_REFERER']);exit;
        $refererhost = parse_url($_SERVER['HTTP_REFERER']);
        $refererhost['host'] .= !empty($refererhost['port']) ? (':' . $refererhost['port']) : '';

        if ($refererhost['host'] != @$_SERVER['HTTP_HOST']) {
            exit('Access Denied');
        } 
        */
		
		//error_reporting( 0 );

        @dheader("Expires: -1");
        @dheader("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", false);
        @dheader("Pragma: no-cache");

        include_once 'include/seccode.class.php';
        $code = new seccode();
        $code -> code = $seccode;
        $code -> type = 2;//$seccodedata['type'];
        $code -> width = $seccodedata['width'];
        $code -> height = $seccodedata['height'];
        $code -> background = $seccodedata['background'];
        $code -> adulterate = $seccodedata['adulterate'];
        $code -> ttf = $seccodedata['ttf'];
        $code -> angle = $seccodedata['angle'];
        $code -> color = $seccodedata['color'];
        $code -> size = $seccodedata['size'];
        $code -> shadow = $seccodedata['shadow'];
        $code -> animator = $seccodedata['animator'];
        $code -> fontpath = _SITE_ROOT_ . './images/fonts/';
        $code -> datapath = _SITE_ROOT_ . './images/seccode/';
        $code -> includepath = _SITE_ROOT_ . './include/';
        $code -> display();
    }
    public static function check( $verifyName, $secc, $renew=true ){
        require_once 'config.php';
        return formcheck( $verifyName, $secc, $renew );
    }
}
?>
