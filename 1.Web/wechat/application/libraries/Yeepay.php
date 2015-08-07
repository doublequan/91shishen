<?php
/**
 * 易宝支付
 * @author LiuPF<mail@phpha.com>
 * @date 2014-10-5
 */
require_once 'Encypt.php';
class Yeepay{
	
	//支付配置
	private static $config = array(
		'p1_MerId'		=> '10001126856',
		'merchantKey'	=> '69cl522AV6q613Ii4W6u8K6XuW8vM1N6bFgyv769220IuYe9u37N4y7rI4Pl',
		'reqURL_onLine'	=> 'https://www.yeepay.com/app-merchant-proxy/node?',
		'callback'		=> 'http://www.100hl.com/callback/yeepay',
	    'card_request'  => 'https://c.yeepay.com/prepay-interface/direct.action',
	    'card_key'      => '0587Tsc81qkpo6IP7H8774Nk6d9Cr1b1ZL6t20G73v3tj4949KiN4sEuCzx6',
	    'card_notify'   => 'http://www.100hl.com/callback/card'
	);
	
	/**
	 * 易宝网银支付|已禁用
	 * @param float $money
	 * @param string $pay_no
	 * @return boolean
	 */
	private function pay($money = 0.00, $pay_no = ''){
		//格式检查
		if($money <= 0 || empty($pay_no))	return false;
		//请求参数
		$data['p0_Cmd'] = 'Buy';
		$data['p1_MerId'] = self::$config['p1_MerId'];
		$data['p2_Order'] = $pay_no;
		$data['p3_Amt'] = $money;
		$data['p4_Cur'] = 'CNY';
		$data['p5_Pid'] = iconv('UTF-8', 'GB2312', '惠生活在线支付');
		$data['p6_Pcat'] = '';
		$data['p7_Pdesc'] = '';
		$data['p8_Url'] = self::$config['callback'];
		$data['p9_SAF'] = '0';
		$data['pa_MP'] = '';
		$data['pd_FrpId'] = ''; //直连设置
		$data['pr_NeedResponse'] = '1'; //启用异步通知
		//签名数据
		$data['hmac'] = self::getReqHmac($data['p2_Order'], $data['p3_Amt'], $data['p4_Cur'], $data['p5_Pid'], $data['p6_Pcat'], $data['p7_Pdesc'], $data['p8_Url'], $data['pa_MP'], $data['pd_FrpId'], $data['pr_NeedResponse']);
		//发起支付
		$pay_url = self::$config['reqURL_onLine'].http_build_query($data);
		redirect($pay_url);
	}
	
	/**
	 * 会员卡信息查询
	 * @param string $card_no
	 * @return boolean|array
	 */
	public function query($card_no = ''){
	    //参数检查
	    if(empty($card_no)){
	        return false;
	    }
	    //处理参数
	    $rep = new Crypt3Des();
	    $data['p0_cmd'] = 'INFO';
	    $data['p1_merid'] = 'FA45255';
	    $data['p2_cardno'] = $rep->encrypt($card_no);
	    $data['p3_serialnumber'] = createBusinessID('QUE');
	    $data['hmac'] = hash_hmac('md5', $data['p0_cmd'].$data['p1_merid'].$card_no.$data['p3_serialnumber'], self::$config['card_key']);
	    //发送请求
	    $str = self::curlPost(self::$config['card_request'], $data, 3);
	    if($str === false)     return false;
	    //处理结果
	    //file_put_contents(APPPATH.'yeepay_debug.txt', var_export($data, true).PHP_EOL.$str.PHP_EOL, FILE_APPEND);
	    $result = array();
	    $arr = explode("\n", $str);
	    if(empty($arr))    return $result;
	    foreach($arr as $v){
	        if(empty($v))  continue;
	        $t = explode('=', trim($v));
	        if(isset($t[0])){
	            $result[$t[0]] = isset($t[1]) ? $t[1] : '';
	        }
	    }
	    //返回支付结果数据
	    return $result;
	}
	
	/**
	 * 易宝会员卡支付
	 * @param float $money
	 * @param string $order_id
	 * @param string $card_no
	 * @param string $card_pass
	 * @return boolean|array
	 */
	public function cardpay($price = 0.00, $order_id = '', $card_no = '', $card_pw = ''){
	    //参数检查
	    if($price <= 0 || empty($order_id) || empty($card_no) || empty($card_pw)){
	        return false;
	    }
	    //处理参数
        $rep = new Crypt3Des();
        $data['p0_cmd'] = 'BUYDIRECT';
        $data['p1_merid'] = 'FA45255';
        $data['p2_requestid'] = $order_id;
	    $data['p3_amount'] = sprintf('%.2f', $price);
	    $data['p4_currency'] = 'CNY';
	    $data['p8_url'] = self::$config['card_notify'];
	    $data['p9_cardno'] = $rep->encrypt($card_no);
	    $data['p10_cardpwd'] = $rep->encrypt($card_pw);
	    $data['p11_ordertime'] = date('YmdHis');
	    $data['hmac'] = hash_hmac('md5', $data['p0_cmd'].$data['p1_merid'].$data['p2_requestid'].$data['p3_amount'].$data['p4_currency'].$data['p8_url'].$card_no.$card_pw.$data['p11_ordertime'], self::$config['card_key']);
	    //发送请求
	    $str = self::curlPost(self::$config['card_request'], $data);
	    if($str === false)     return false;
	    //处理结果
	    //file_put_contents(APPPATH.'yeepay_debug.txt', var_export($data, true).PHP_EOL.$str.PHP_EOL, FILE_APPEND);
	    $result = array();
	    $arr = explode("\n", $str);
	    if(empty($arr))    return $result;
	    foreach($arr as $v){
	        if(empty($v))  continue;
            $t = explode('=', trim($v));
            if(isset($t[0])){
                $result[$t[0]] = isset($t[1]) ? $t[1] : '';
            }
        }
	    //返回支付结果数据
	    return $result;
	}
	
	/**
	 * 检查响应签名
	 * @param array $data
	 * @return boolean
	 */
	public function checkHmac($data = array()){
	    //生成签名
	    $checkHmac = self::getResHmac($data['r0_Cmd'], $data['r1_Code'], $data['r2_TrxId'], $data['r3_Amt'], $data['r4_Cur'], $data['r5_Pid'], $data['r6_Order'], $data['r7_Uid'], $data['r8_MP'], $data['r9_BType']);
	    //检查签名
	    return ($checkHmac == $data['hmac'] && $data['r1_Code'] == '1') ? true : false;
	}
	
	/**
	 * CURL模拟POST请求
	 * @param string $url
	 * @param array $data
	 * @param integer $time_out
	 * @return boolean|string
	 */
	private static function curlPost($url = '', $data = array(), $time_out = 30){
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
	    curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    $str = curl_exec($ch);
	    if(curl_errno($ch))    return false;
	    curl_close ($ch);
	    return $str;
	}
	
	/**
	 * 生成请求签名
	 * @param string $p2_Order
	 * @param string $p3_Amt
	 * @param string $p4_Cur
	 * @param string $p5_Pid
	 * @param string $p6_Pcat
	 * @param string $p7_Pdesc
	 * @param string $p8_Url
	 * @param string $pa_MP
	 * @param string $pd_FrpId
	 * @param string $pr_NeedResponse
	 * @return string
	 */
	private static function getReqHmac($p2_Order, $p3_Amt, $p4_Cur, $p5_Pid, $p6_Pcat, $p7_Pdesc, $p8_Url, $pa_MP, $pd_FrpId, $pr_NeedResponse){
		$sbOld = "";
		$p0_Cmd = 'Buy';
		$p9_SAF = '0';
		//加入业务类型
		$sbOld = $sbOld.$p0_Cmd;
		//加入商户编号
		$sbOld = $sbOld.self::$config['p1_MerId'];
		//加入商户订单号
		$sbOld = $sbOld.$p2_Order;
		//加入支付金额
		$sbOld = $sbOld.$p3_Amt;
		//加入交易币种
		$sbOld = $sbOld.$p4_Cur;
		//加入商品名称
		$sbOld = $sbOld.$p5_Pid;
		//加入商品分类
		$sbOld = $sbOld.$p6_Pcat;
		//加入商品描述
		$sbOld = $sbOld.$p7_Pdesc;
		//加入回调地址
		$sbOld = $sbOld.$p8_Url;
		//加入送货地址标识
		$sbOld = $sbOld.$p9_SAF;
		//加入商户扩展信息
		$sbOld = $sbOld.$pa_MP;
		//加入支付通道编码
		$sbOld = $sbOld.$pd_FrpId;
		//加入是否需要应答机制
		$sbOld = $sbOld.$pr_NeedResponse;
		return self::HmacMd5($sbOld, self::$config['merchantKey']);
	
	}
	
	/**
	 * 生成响应签名
	 * @param string $r0_Cmd
	 * @param string $r1_Code
	 * @param string $r2_TrxId
	 * @param string $r3_Amt
	 * @param string $r4_Cur
	 * @param string $r5_Pid
	 * @param string $r6_Order
	 * @param string $r7_Uid
	 * @param string $r8_MP
	 * @param string $r9_BType
	 * @return string
	 */
	private static function getResHmac($r0_Cmd, $r1_Code, $r2_TrxId, $r3_Amt, $r4_Cur, $r5_Pid, $r6_Order, $r7_Uid, $r8_MP, $r9_BType){
		$sbOld = "";
		$p9_SAF = '0';
		//加入商家ID
		$sbOld = $sbOld.self::$config['p1_MerId'];
		//加入消息类型
		$sbOld = $sbOld.$r0_Cmd;
		//加入业务返回码
		$sbOld = $sbOld.$r1_Code;
		//加入交易ID
		$sbOld = $sbOld.$r2_TrxId;
		//加入交易金额
		$sbOld = $sbOld.$r3_Amt;
		//加入货币单位
		$sbOld = $sbOld.$r4_Cur;
		//加入产品Id
		$sbOld = $sbOld.$r5_Pid;
		//加入订单ID
		$sbOld = $sbOld.$r6_Order;
		//加入用户ID
		$sbOld = $sbOld.$r7_Uid;
		//加入商家扩展信息
		$sbOld = $sbOld.$r8_MP;
		//加入交易结果返回类型
		$sbOld = $sbOld.$r9_BType;
		return self::HmacMd5($sbOld, self::$config['merchantKey']);
	
	}
	
	/**
	 * 签名加密算法
	 * @param string $data
	 * @param string $key
	 * @return string
	 */
	private static function HmacMd5($data, $key){
		$data = iconv('GB2312', 'UTF-8', $data);
		$key = iconv('GB2312', 'UTF-8', $key);
		$b = 64;
		if(strlen($key) > $b){
			$key = pack("H*", md5($key));
		}
		$key = str_pad($key, $b, chr(0x00));
		$ipad = str_pad('', $b, chr(0x36));
		$opad = str_pad('', $b, chr(0x5c));
		$k_ipad = $key ^ $ipad;
		$k_opad = $key ^ $opad;
		return md5($k_opad.pack("H*", md5($k_ipad.$data)));
	}
}