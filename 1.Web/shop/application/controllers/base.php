<?php
/**
 * 父控制器
 * @author LiuPF<mail@phpha.com>
 * @date 2014-8-6
 */
class Base extends CI_Controller{
	
	//错误类型
	protected static $errorType = array(
		0	 =>	'success', //请求成功
		1000 =>	'system error', //系统错误
		1001 =>	'captcha error', //验证码错误
		1002 => 'parameter format error', //参数格式错误
		1003 => 'data error', //数据错误|比如ID不存在|登录密码错误
		1004 => 'data repeat', //数据重复|比如手机号已存在
		1010 => 'access denied', //禁止访问|比如未登录
	);
	
	//邮件配置
	private static $emailConfig = array(
		'crlf' => "\r\n",
		'newline' => "\r\n",
		'charset' => 'utf-8',
		'mailtype' => 'html',
		'protocol' => 'smtp',
		'useragent' => 'www.100hl.com',
		'smtp_host' => 'smtp.qq.com',
		'smtp_user' => 'service@100hl.cn',
		'smtp_pass' => 'hsh2014',
		'smtp_port' => '25',
		'smtp_timeout' => '5'
	);
	
	//订单状态
	protected static $orderStatus = array(
	    0 => '待支付', //新订单
	    1 => '待确认', //支付成功
	    2 => '货到付款',
	    3 => '非法支付', //支付异常
	    10 => '已删除', //用户删除
	    11 => '已取消', //用户取消
	    12 => '已取消', //管理员取消
	    20 => '已完成', //订单完成
		21 => '已确认', //后台确认
		25 => '商品出库', //商品出库
		27 => '等待收货', //开始配送
	);
	
	//支付方式
	protected static $payType = array(
	    1 => '货到付款',
	    2 => '支付宝',
	    3 => '会员卡'
	);
	
	//构造函数
	public function __construct(){
		parent::__construct();
		$this->load->model('Base_model');
		$this->load->model('Cart_model');
	    $this->load->model('Category_model');
		$this->load->helper('url');
		$this->load->library('session');
		//记住密码自动登陆
		$this->_autoLogin();
		//购物车商品总数
		$cartNum = $vipCartNum = 0;
		$cart = json_decode($this->input->cookie('cart',true), true);
		$vipCart = json_decode($this->input->cookie('vipcart',true), true);
		if( ! empty($cart)){
		    foreach($cart as $v){
		        $cartNum += $v['amount'];
		    }
		}
		if( ! empty($vipCart)){
		    foreach($vipCart as $v){
		        $vipCartNum += $v['amount'];
		    }
		}
		//所有商品分类
		$allCategories = $this->Category_model->getGoodsCategory();
		//Logo、导航栏、热门搜索关键词
		$fragments = array();
		$placesAll = array(
			1	=> array(2,3,20,119),
			2	=> array(58,59,64,120),
			3	=> array(89,90,95,121),
		);
		$places = $placesAll[SITEID];
		$condition = array(
			'AND' => array('place_id IN ('.implode(',', $places).')'),
		);
		$res = $this->Base_model->getList('fragments',$condition,'*','place_id ASC,sort ASC');
		if( $res ){
			$places_flip = array_flip($places);
			foreach ( $res as $row ){
				$key = $places_flip[$row['place_id']];
				$place_id = $placesAll[1][$key];
				$fragments[$place_id][] = $row;
			}
		}
		//预定义变量
		$data = array(
		    'siteName' => '惠生活',
		    'cartNum' => $cartNum,
		    'vipCartNum' => $vipCartNum,
		    'currMenu' => 'member_order',
		    'allCategories' => $allCategories,
			'logo' => isset($fragments[3]) ? $fragments[3] : array(),
			'nav' => isset($fragments[2]) ? $fragments[2] : array(),
			'keywords' => isset($fragments[20]) ? $fragments[20] : array(),
			'top' => isset($fragments[119]) ? $fragments[119] : array(),
		);
		$this->load->vars($data);
	}
	
	/**
	 * 记住密码自动登录
	 * @param
	 * @return boolean
	 */
	protected function _autoLogin(){
	    $user = $this->session->userdata('userinfo');
	    $remPwd = unserialize($this->input->cookie('REMPWD', true));
	    if(false != $user)     return false;
	    if(empty($remPwd['u']) || empty($remPwd['p']))     return false;
	    $user = $this->Base_model->getRow("SELECT id,username,pass FROM users WHERE username = '{$remPwd['u']}' AND status = 1");
	    if(empty($user)){
	        //删除Cookie
	        $this->input->set_cookie('REMPWD', '', '');
	        return false;
	    }
	    //检查Cookie
	    $check_cookie = md5($user['pass'].REMPWD_SALT);
	    if($check_cookie == $remPwd['p']){
	        //设置SESSION
	        $userInfo = array('uid'=>$user['id'], 'username'=>$user['username']);
	        $this->session->set_userdata('userinfo', $userInfo);
	        //更新购物车
	        $cart = json_decode($this->input->cookie('cart', true), true);
	        if(empty($cart)){
	            $this->Cart_model->syncToCart($userInfo['uid']);
	        }else{
	            $this->Cart_model->syncToDb($userInfo['uid'], $cart);
	        }
	    }else{
	        //删除Cookie
	        $this->input->set_cookie('REMPWD', '', '');
	    }
	}
	
	/**
	 * 发送邮件
	 * @param string $email
	 * @param string $subject
	 * @param string $message
	 * @param boolean $debug
	 * @return boolean
	*/
	protected function _sendEmail($email = '', $subject = '', $message = '', $debug = false){
		if(empty($email) || empty($message))	return false;
// 		$this->load->library('email');
// 		$this->email->initialize(self::$emailConfig);
// 		$this->email->from(self::$emailConfig['smtp_user'], '惠生活');
// 		$this->email->to($email);
// 		$this->email->subject($subject);
// 		$this->email->message($message);
// 		$result = $this->email->send();
// 		if($debug){
// 			echo $this->email->print_debugger();
// 		}
        //插入队列
        $queue['email'] = $email;
        $queue['subject'] = $subject;
        $queue['content'] = $message;
        $queue['create_time'] = time();
        $result = $this->Base_model->insert('queue_email', $queue);
		return empty($result['id']) ? false : true;
	}
	
	/**
	 * 页面跳转
	 * @param string $message
	 * @param string $url
	 * @param boolean $success
	 */
	protected function _redirect($message, $url, $success = true){
		$this->load->view('common/redirect', array('message'=>$message, 'url'=>$url, 'success'=>$success));
	}
	
	/**
	 * 格式化分页
	 * @param string $base_url
	 * @param array $pager 分页信息
	 * @return string 分页格式化输出
	 */
	protected function _formatPager($base_url = '', $pager = array()){
		if(empty($pager['pages']))	return '';
		$next_page = min($pager['pages'], $pager['page'] + 1);
		$prev_page = max(1, $pager['page'] - 1);
		$delimiter = strpos($base_url, '?') ? '&' : '?';
		$pageArray = '';
		foreach($pager['pageArray'] as $v){
			if($v == $pager['page']){
				$pageArray .= "<a href=\"{$base_url}{$delimiter}page={$v}\" class=\"page_number cur\">{$v}</a>";
			}else{
				$pageArray .= "<a href=\"{$base_url}{$delimiter}page={$v}\" class=\"page_number\">{$v}</a>";
			}
		}
		$format = <<<EOT
<a href="{$base_url}{$delimiter}page=1">首页</a><a href="{$base_url}{$delimiter}page={$prev_page}">上一页</a>
{$pageArray}
<a href="{$base_url}{$delimiter}page={$next_page}">下一页</a><a href="{$base_url}{$delimiter}page={$pager['pages']}">末页</a>
EOT;
		return $format;
	}
}