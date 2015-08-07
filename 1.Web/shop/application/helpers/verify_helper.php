<?php
/**
 * 格式验证函数库
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-6
 */

/**
 * 验证用户名
 * @param string $subject
 * @return boolean
 */
function isUsername($subject = ''){
	return strlen($subject)>=2 ? true : false; 
	$pattern = "/^[a-z][a-z0-9]{5,19}$/";
	if(preg_match($pattern, $subject)){
		return true;
	}
	return false;
}

/**
 * 验证密码
 * @param string $subject
 * @return boolean
 */
function isPassword($subject = ''){
	return strlen($subject)>=6 ? true : false;
	$pattern = "/^[a-zA-Z0-9!@#\\$%\\^&*()_]{6,20}$/";
	if(preg_match($pattern, $subject)){
		return true;
	}
	return false;
}

/**
 * 验证手机
 * @param string $subject
 * @return boolean
 */
function isMobile($subject = ''){
	$pattern = "/^1[3|5|7|8][0-9]{9}$/";
	if(preg_match($pattern, $subject)){
		return true;
	}
	return false;
}

/**
 * 验证固话
 * @param string $subject
 * @return boolean
 */
function isTelephone($subject = ''){
	$pattern = "/^[0-9]{3,4}-?[0-9]{8}$/";
	if(preg_match($pattern, $subject)){
		return true;
	}
	return false;
}

/**
 * 验证邮编
 * @param string $subject
 * @return boolean
 */
function isZipcode($subject = ''){
	$pattern = "/^[1-9][0-9]{5}$/";
	if(preg_match($pattern, $subject)){
		return true;
	}
	return false;
}

/**
 * 验证QQ
 * @param string $subject
 * @return boolean
 */
function isQq($subject = ''){
	$pattern = "/^[1-9][0-9]{4,9}$/";
	if(preg_match($pattern, $subject)){
		return true;
	}
	return false;
}

/**
 * 验证邮箱
 * @param string $subject
 * @return boolean
 */
function isEmail($subject = ''){
	$pattern = "/^([a-zA-Z0-9]+[_|\\_|\\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\\_|\\.]?)*[a-zA-Z0-9]+\\.[a-zA-Z]{2,4}$/";
	if(preg_match($pattern, $subject)){
		return true;
	}
	return false;
}

/**
 * 邮箱验证码
 * @param string $subject
 * @return boolean
 */
function isEmailVerify($subject = ''){
    $pattern = "/^[a-z0-9]{32}$/";
    if(preg_match($pattern, $subject)){
        return true;
    }
    return false;
}

/**
 * 短信验证码
 * @param string $subject
 * @return boolean
 */
function isMobileVerify($subject = ''){
    $pattern = "/^[0-9]{6}$/";
    if(preg_match($pattern, $subject)){
        return true;
    }
    return false;
}

/**
 * 验证会员卡号
 * @param string $subject
 * @return boolean
 */
function isCard($subject = ''){
	$pattern = "/^[0-9]{6,20}$/";
	if(preg_match($pattern, $subject)){
		return true;
	}
	return false;
}

/**
 * 验证支付单号
 * @param string $subject
 * @return boolean
 */
function isPaymentId($subject = ''){
	$pattern = "/^PAY[0-9]{17}$/";
	if(preg_match($pattern, $subject)){
		return true;
	}
	return false;
}

/**
 * 验证日期
 * @param string $subject
 * @return boolean
 */
function isDate($subject = ''){
	$pattern = "/^(19|20)[0-9]{2}-([1-9]|1[0-2])-([1-9]|[1-2][0-9]|3[0-1])$/";
	if(preg_match($pattern, $subject)){
		return true;
	}
	return false;
}

/**
 * 验证中文
 * @param string $subject
 * @return boolean
 */
function isChinese($subject = ''){
	$pattern = "/^[\x{4e00}-\x{9fa5}]+$/u";
	if(preg_match($pattern, $subject)){
		return true;
	}
	return false;
}