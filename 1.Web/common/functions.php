<?php

/**
 * page query without database
 * @param integer $total
 * @param integer $page
 * @param integer $size
 * @return Ambigous <boolean, array>
 */
function pageArray( $total, $page=1, $size=10 ){
	$pager = false;
	if( $total>0 ){
		$pages = $total%$size ? intval($total/$size)+1 : intval($total/$size);
		$pager['total'] = $total;
		$pager['pages'] = $pages;
		$pager['page'] = $page;
		$pager['size'] = $size;
		$pager['next'] = $page==$pages ? 0 : $page+1;
		$pager['prev'] = $page==1 ? 0 : $page-1;
		$pager['pagesize'] = $page==$pages ? $total-($page-1)*$size : 10;
		if( $pages <= 7 ){
			for($i=1;$i<=$pages;$i++){
				$pager['pageArray'][] = $i;
			}
		} else {
			for( $i=$page-3; $i<=$page+3; $i++ ){
				if( $page<=3 ){
					$j = $i+4-$page;
				} elseif ( $page>3 && $page<$pages-3 ){
					$j = $i;
				} else {
					$j = $i+$pages-$page-3;
				}
				$pager['pageArray'][] = $j;
			}
		}
	}
	return $pager;
}

/**
 * create single insert SQL statement
 * @param string $table
 * @param array $params
 * @return string
 */
function dbInsert($table, $params) {
	$columns = "INSERT INTO `" . $table . "`(";
	$values = " VALUES(";
	foreach ( $params as $k => $v ) {
		$columns .= '`'.$k . '`,';
		$values .= "'" . $v . "',";
	}
	$columns = substr ( $columns, 0, - 1 );
	$values = substr ( $values, 0, - 1 );
	return $columns . ") " . $values . ")";
}

/**
 * create multi insert SQL statement
 * @param string $table
 * @param array $params
 * @return string
 */
function dbInsertBatch($table, $params){
	if ( !isset($params[0]) || !is_array($params[0]) ) {
		return false;
	}
	$columns = '`(';
	foreach ( $params[0] as $k=>$v ) {
		$columns .= '`'.$k . '`,';
	}
	$columns = substr($columns,0,-1).')';
	$sql = "INSERT INTO `" . $table . $columns . " VALUES";
	$values = '';
	foreach ( $params as $row ) {
		$values .= '(';
		foreach( $row as $k=>$v ){
			$values .= "'" . $v . "',";
		}
		$values = substr($values,0,-1).'),';
	}
	$sql = substr($sql.$values,0,-1);
	return $sql;
}

/**
 * create single update SQL statement
 * @param string $table
 * @param array $params
 * @param string $where
 * @return string
 */
function dbUpdate($table, $params, $where) {
	$str = "UPDATE `" . $table . "` SET ";
	foreach ( $params as $k => $v ) {
		$str .= "`{$k}`='{$v}',";
	}
	$str = substr ( $str, 0, - 1 );
	return $str . " WHERE " . $where;
}

/**
 * get user IP address
 * @param boolean $type
 * @return string
 */
function getUserIP( $type=false ){
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $ip = $_SERVER["HTTP_CLIENT_IP"]; 
    } else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))  {
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];  
    }
    else if(!empty($_SERVER["REMOTE_ADDR"]))  {
        $ip = $_SERVER["REMOTE_ADDR"];
    } else {
        $ip = "127.0.0.1";
    }
    return $type ? $ip : bindec(decbin(ip2long($ip)));
}

/**
 * get last visit URL
 * @return string
 */
function getBackUrl(){
	$backurl = isset ($_REQUEST ["backurl"]) ? $_REQUEST ["backurl"] : false;
	if ( !$backurl ) {
		$backurl = getReferer();
	}
	if ( !$backurl ) {
		$backurl = base_url();
	}
	$backurl = filter_var( $backurl, FILTER_SANITIZE_STRING );
	return $backurl;
}

/**
 * get referer
 * @return Ambigous <boolean, unknown>
 */
function getReferer() {
	return isset ($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : false;
}

/**
 * encrypt user password
 * @param string $pass
 * @param string $salt
 * @return string
 */
function encryptPass( $pass, $salt ) {
	return md5($pass);
    //return substr( sha1( md5( md5( $pass ).$salt ).strrev($salt) ), 0, 32 );
}


/**
 * get random string
 * @param integer $count
 * @return string
 */
function getRandStr( $length=6 ) {
    $str = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $ret = '';
    $len = strlen($str)-1;
    for( $i=1; $i<=$length; $i++ ) {
        $start = rand(0, $len);
        $ret .= substr( $str, $start, 1 );
    }
    return $ret;
}


/**
 * encode & decode string
 * @param string $string
 * @param string $operation
 * @return string
 */
function authcode($string, $operation = 'DECODE') {
    $key = md5("ve39P5g3wjhilfzZ".$_SERVER['HTTP_USER_AGENT']);
    $expiry = 0;
    
    $ckey_length = 4;

    $key = md5 ( $key ? $key : "8ulHi8WRE0+FavjTpsqMY-Ryg7LGoUFA" );
    $keya = md5 ( substr ( $key, 0, 16 ) );
    $keyb = md5 ( substr ( $key, 16, 16 ) );
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr ( $string, 0, $ckey_length ) : substr ( md5 ( microtime () ), - $ckey_length )) : '';

    $cryptkey = $keya . md5 ( $keya . $keyc );
    $key_length = strlen ( $cryptkey );

    $string = $operation == 'DECODE' ? base64_decode ( substr ( $string, $ckey_length ) ) : sprintf ( '%010d', $expiry ? $expiry + time () : 0 ) . substr ( md5 ( $string . $keyb ), 0, 16 ) . $string;
    $string_length = strlen ( $string );

    $result = '';
    $box = range ( 0, 255 );

    $rndkey = array ();
    for($i = 0; $i <= 255; $i ++) {
        $rndkey [$i] = ord ( $cryptkey [$i % $key_length] );
    }

    for($j = $i = 0; $i < 256; $i ++) {
        $j = ($j + $box [$i] + $rndkey [$i]) % 256;
        $tmp = $box [$i];
        $box [$i] = $box [$j];
        $box [$j] = $tmp;
    }

    for($a = $j = $i = 0; $i < $string_length; $i ++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box [$a]) % 256;
        $tmp = $box [$a];
        $box [$a] = $box [$j];
        $box [$j] = $tmp;
        $result .= chr ( ord ( $string [$i] ) ^ ($box [($box [$a] + $box [$j]) % 256]) );
    }

    if ($operation == 'DECODE') {
        if ((substr ( $result, 0, 10 ) == 0 || substr ( $result, 0, 10 ) - time () > 0) && substr ( $result, 10, 16 ) == substr ( md5 ( substr ( $result, 26 ) . $keyb ), 0, 16 )) {
            return substr ( $result, 26 );
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace ( '=', '', base64_encode ( $result ) );
    }
}

/**
 * @param string $qt
 * @param array $params
 * @return boolean|string
 */
function curlRequest( $url, $params=array(), $header=array() ){
	$ch = curl_init();
	$res= curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	if( isset($_SERVER['HTTP_USER_AGENT']) ){
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	}
	if( $params ){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
	}
	if( $header ){
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	}
	$result = curl_exec ($ch);
	curl_close($ch);
	if ($result == NULL) {
		return false;
	}
	return $result;
}

/**
 * @param string $ip
 * @return array|boolean
 */
function getRealAddress($ip){
	$url = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
	$res = curlRequest( $url );
	$res = json_decode( $res, true );
	if( $res && $res['code']==0 ){
		return $res['data'];
	}
	return false;
}

function createOrderID( $uid=0 ){
	if( $uid<100 ){
		$uid = mt_rand(10,99);
	} else {
		$uid = $uid%100;
	}
	return date('YmdHis').$uid.mt_rand(1000, 9999);
}

/**
 * @param string $prefix PUR:Purchase, DIS:Dispatch, RET:Return, Fin:finance
 * @return string
 */
function createBusinessID($prefix=''){
	$prefix = $prefix=='' ? 'ERP' : strtoupper($prefix);
	return $prefix.date('YmdHis').mt_rand(100, 999);
}

/**
 * upload single file
 */
function uploadFile( $file='name' ){
	$ret = array('path'=>'','url'=>'');
	do{
		if( !isset($_FILES[$file]['name']) ){
			break;
		}
		
		$allowType = explode(',', FILE_EXTENSIONS);
		$fileType = strtolower(strrchr($_FILES[$file]['name'],'.'));
		$fileType = substr($fileType,1);
		if( !in_array($fileType, $allowType) ){
			break;
		}
		
		$relaPath = date('Y/m/');
		$fullPath = FILE_PATH . $relaPath;
		if( !file_exists( $fullPath ) ) {
			mkdir($fullPath, 0777, true);
		}
		$fileName = time().getRandStr(6).'.'.$fileType;
		$result = move_uploaded_file( $_FILES[$file]['tmp_name'], $fullPath.$fileName );
		if( $result ){
			$ret = array(
				'file'	=> $fullPath.$fileName,
				'url'	=> FILE_DOMAIN . $relaPath . $fileName,
			);
		}
	}while(0);
	return $ret;
}

/**
 * upload multi file
 */
function uploadFileArray( $file ){
	$ret = array();
	$count = count($_FILES[$file]['name']);
	$ret = array();
	for( $i=0; $i<$count; $i++ ){
		do{
			if( !isset($_FILES[$file]['name'][$i]) ){
				break;
			}
		
			$allowType = explode(',', FILE_EXTENSIONS);
			$fileType = strtolower(strrchr($_FILES[$file]['name'],'.'));
			$fileType = substr($fileType,1);
			if( !in_array($fileType, $allowType) ){
				break;
			}
		
			$relaPath = date('Y/m/');
			$fullPath = FILE_PATH . $relaPath;
			if( !file_exists( $fullPath ) ) {
				mkdir($fullPath, 0777, true);
			}
			$fileName = time().getRandStr(6).'.'.$fileType;
			$result = move_uploaded_file( $_FILES[$file]['tmp_name'], $fullPath.$fileName );
			if( $result ){
				$ret[] = array(
					'file'	=> $fullPath,
					'url'	=> FILE_DOMAIN . $relaPath . $fileName,
				);
			}
		}while(0);
	}
	return $ret;
}

function getImageInfo( $img ){
	$imageInfo = getimagesize($img);
	if( $imageInfo!== false) {
		$imageType = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
		$info = array(
			"width"		=>$imageInfo[0],
			"height"	=>$imageInfo[1],
			"type"		=>$imageType,
			"mime"		=>$imageInfo['mime'],
		);
		return $info;
	}else {
		return false;
	}
}

/**
* @desc create user avatar URL
* @param string $finger
* @return string
*/
function getAvatar( $uid ){
	$avater = 'avatar/default.gif';
	$finger = md5($uid);
	$finger = substr_replace($finger, '/', 6, 0);
	$finger = substr_replace($finger, '/', 4, 0);
	$finger = substr_replace($finger, '/', 2, 0);
	$t = 'avatar/'.$finger.'/'.$uid.'.jpg';
	$real_path = SYSTEM_PATH.'shop/'.$t;
	if( file_exists($real_path) ){
		$avater = $t;
	}
	return AVATAR_DOMAIN . $avater;
}

/**
 * 创建多级目录
 * @param string $dir
 * @return boolean
 */
function mkdirs($dir){
    return is_dir($dir) || (mkdirs(dirname($dir)) && mkdir($dir, 0777));
}

function getConfig( $key ){
	$cache_key = 'SYSTEM_CONFIG_'.$key;
	$ret = Common_Cache::get($cache_key);
	$ret = false;
	if( !$ret ){
		global $config;
		$ret = $config[$key];
		Common_Cache::save($cache_key, $ret, 60);
	}
	return $ret;
}

function getOrderDay( $type ){
	$t = time();
	$date = getdate($t);
	$wday = $date['wday'];
	$plus = false;
	if( $type==1 ){
		$plus = 86400;
	} elseif ( $type==2 ){
		if( $wday==5 ){
			$plus = 86400*3;
		} else {
			$plus = 86400;
		}
	} elseif( $type==3 ) {
		if( $wday==6 ){
			$plus = 86400;
		} elseif ( $wday==0 ){
			$plus = 86400*6;
		} else {
			$plus = 86400*(6-$wday);
		}
	}
	return $plus ? date('Y-m-d',$t+$plus) : false;
}
