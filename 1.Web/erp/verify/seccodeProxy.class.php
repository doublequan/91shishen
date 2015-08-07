<?php

class seccodeProxy {
    public static function generateImg( $verifyName ) {
        require_once dirname(__FILE__).'/config.php';

        $_SESSION[$verifyName] = $seccode = random(6, 1);
        
		
		//error_reporting( 0 );

        @dheader("Expires: -1");
        @dheader("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", false);
        @dheader("Pragma: no-cache");

        include_once 'include/seccode.class.php';
        $code = new seccode();
        $code -> code = $seccode;
        $code -> type = $seccodedata['type'];
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
        require_once dirname(__FILE__).'/config.php';
        return formcheck( $verifyName, $secc, $renew );
    }
}
?>
