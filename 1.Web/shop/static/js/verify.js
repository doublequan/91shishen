/**
 * 格式验证函数库
 * author LiuPF<mail@phpha.com>
 * date 2014-8-7
 */

//验证用户名
function isUsername(subject){
	return subject.length>=2 ? true : false;
	//6-20位小写字母数字且以字母开头
	//var pattern = /^[a-z][a-z0-9]{5,19}$/;
	//return pattern.test(subject);
}

//验证密码
function isPassword(subject){
	return subject.length>=6 ? true : false;
	//6-20位字母数字特殊符号
	//var pattern = /^[a-zA-Z0-9!@#\$%\^&*()_]{6,20}$/;
	//return pattern.test(subject);
}

//验证码
function isCaptcha(subject){
	//6位数字
	var pattern = /^[0-9]{6}$/;
	return pattern.test(subject);
}

//验证手机
function isMobile(subject){
	var pattern = /^1[3|5|7|8][0-9]{9}$/;
	return pattern.test(subject);
}

//验证固话
function isTelephone(subject){
	var pattern = /^[0-9]{3,4}-?[0-9]{8}$/;
	return pattern.test(subject);
}

//验证邮编
function isZipcode(subject){
	var pattern = /^[1-9][0-9]{5}$/;
	return pattern.test(subject);
}

//验证邮箱
function isEmail(subject){
	var pattern = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,4}$/;
	return pattern.test(subject);
}

//邮箱验证码
function isEmailVerify(subject){
	var pattern = /^[a-z0-9]{32}$/;
	return pattern.test(subject);
}

//短信验证码
function isMobileVerify(subject){
	var pattern = /^[0-9]{6}$/;
	return pattern.test(subject);
}

//验证QQ
function isQq(subject){
	var pattern = /^[1-9][0-9]{4,9}$/;
	return pattern.test(subject);
}

//会员卡号
function isCard(subject){
	var pattern = /^[0-9]{6,20}$/;
	return pattern.test(subject);
}
