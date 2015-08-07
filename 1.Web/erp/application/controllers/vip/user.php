<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class user extends Base 
{
	private $active_top_tag = 'vip';
	
	//Common Companys
	public $companys = array();

	public function __construct(){
		parent::__construct();
		$res = $this->mBase->getList('vip_companys',array('AND'=>array('is_del=0')));
		if( $res ){
			foreach ( $res as $row ){
				$this->companys[$row['id']] = $row;
			}
		}
	}

	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('company_id','page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$params['company_id'] = intval($params['company_id']);
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get users
		$condition = array(
			'AND' => array('is_del=0'),
		);
		if( $params['company_id'] ){
			$condition['AND'][] = 'company_id='.$params['company_id'];
		}
		
		$res = $this->mBase->getList('vip_users',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//Common Data
		$data['companys'] = $this->companys;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'user';
		$this->_view('common/header', $tags);
		$this->_view('vip/user_list', $data);
		$this->_view('common/footer');
	}
	
	public function add(){
		$data = array();
		//Common Data
		$data['companys'] = $this->companys;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'user_add';
		$this->_view('vip/user_add', $data);
	}
	
	public function edit(){
		$data = array();
		//get parameters
		$must = array('id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$id = intval($params['id']);
		//get product
		$data['single'] = $this->mBase->getSingle('vip_users','id',$id);
		//Common Data
		$data['companys'] = $this->companys;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'user_edit';
		$this->_view('vip/user_edit', $data);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
        do{
        	//get parameters
        	$must = array('company_id','username','mobile','pass','repass','discount');
        	$fields = array('position','deal_eid','charge_eid');
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	$params['company_id'] = intval($params['company_id']);
        	//check parameters
        	if( !array_key_exists($this->params['company_id'],$this->companys) ){
        		$ret = array('err_no'=>1000,'err_msg'=>'大客户所属公司不存在');
        		break;
        	}
        	$params['username'] = strtolower($params['username']);
        	if( !preg_match('/^[0-9a-z]{6,20}$/',$params['username']) ){
        		$ret = array('err_no'=>1000,'err_msg'=>'用户名格式错误，请使用6至20位的小写字母或者数字');
        		break;
        	}
        	$user = $this->mBase->getSingle('vip_users','username',$params['username']);
        	if( $user ){
        		$ret = array('err_no'=>1000,'err_msg'=>'用户姓名已经存在');
        		break;
        	}
        	if( !preg_match('/^1[\d]{10}$/',$params['mobile']) ){
        		$ret = array('err_no'=>1000,'err_msg'=>'手机号码格式错误');
        		break;
        	}
        	$user = $this->mBase->getSingle('vip_users','mobile',$params['mobile']);
        	if( $user ){
        		$ret = array('err_no'=>1000,'err_msg'=>'手机号码已经存在');
        		break;
        	}
        	if( strlen($params['pass'])<6 ){
        		$ret = array('err_no'=>1000,'err_msg'=>'登录密码不能少于6位');
        		break;
        	}
        	if( $params['pass'] != $params['repass'] ){
        		$ret = array('err_no'=>1000,'err_msg'=>'两次输入的密码不一致');
        		break;
        	}
        	//get employee
        	$deal_name = '';
        	if( $params['deal_eid'] ){
        		$t = $this->mBase->getSingle('employees','id',$params['deal_eid'],'username');
        		if( $t ){
        			$deal_name = $t['username'];
        		}
                else{
                    $ret = array('err_no'=>1000,'err_msg'=>'招商员工不存在');
                    break;
                }
        	}
        	$charge_name = '';
        	if( $params['charge_eid'] ){
        		$t = $this->mBase->getSingle('employees','id',$params['charge_eid'],'username');
        		if( $t ){
        			$charge_name = $t['username'];
        		}
                else{
                    $ret = array('err_no'=>1000,'err_msg'=>'负责员工不存在');
                    break;
                }
        	}
        	$ip = getUserIP();
        	$time = time();
        	$salt = getRandStr(10);
        	$data = array(
        		'company_id'	=> $params['company_id'],
        		'username'		=> $params['username'],
                'mobile'        => $params['mobile'],
        		'position'		=> $params['position'],
        		'pass'			=> encryptPass($params['pass'], $salt),
        		'salt'			=> $salt,
        		'discount'		=> max(1,min(100,$params['discount'])),
        		'deal_eid'		=> $params['deal_eid'],
        		'deal_name'		=> $deal_name,
        		'charge_eid'	=> $params['charge_eid'],
        		'charge_name'	=> $charge_name,
        		'create_eid'	=> parent::$user['id'],
        		'create_name'	=> parent::$user['username'],
        		'create_time'	=> $time,
        	);
            $user = $this->mBase->insert('vip_users',$data);
            if( !$user ){
                $ret = array('err_no'=>1000,'err_msg'=>'添加大客户失败');
        		break;
            }
            $ret = array('err_no'=>0,'err_msg'=>'操作成功');
        } while(0);
        $this->output($ret);
	}
	
	public function doEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
    	do{
    		//get parameters
        	$must = array('id','company_id','discount');
        	$fields = array('pass','repass','position','deal_eid','charge_eid');
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	$id = intval($params['id']);
        	$params['company_id'] = intval($params['company_id']);
        	//check parameters
        	$user = $this->mBase->getSingle('vip_users','id',$id);
        	if( !$user ){
        		$ret = array('err_no'=>1000,'err_msg'=>'大客户不存在');
        		break;
        	}
        	if( !array_key_exists($this->params['company_id'],$this->companys) ){
        		$ret = array('err_no'=>1000,'err_msg'=>'大客户所属公司不存在');
        		break;
        	}
        	if( $params['pass']){
                if( strlen($params['pass'])<6 ){
                    $ret = array('err_no'=>1000,'err_msg'=>'登录密码不能少于6位');
                    break;
                }
        		if( $params['pass'] != $params['repass'] ){
                    $ret = array('err_no'=>1000,'err_msg'=>'两次输入的密码不一致');
                    break;
                }
        	}
        	//get employee
        	$deal_name = $user['deal_name'];
        	if( $params['deal_eid'] && $params['deal_eid']!=$user['deal_eid'] ){
        		$t = $this->mBase->getSingle('employees','id',$params['deal_eid'],'username');
        		if( $t ){
        			$deal_name = $t['username'];
        		}
        	}
        	$charge_name = $user['charge_name'];;
        	if( $params['charge_eid'] && $params['charge_eid']!=$user['charge_eid'] ){
        		$t = $this->mBase->getSingle('employees','id',$params['charge_eid'],'username');
        		if( $t ){
        			$charge_name = $t['username'];
        		}
        	}
        	
        	$data = array(
        		'company_id'	=> $params['company_id'],
                'position'      => $params['position'],
        		'discount'		=> max(1,min(100,$params['discount'])),
        		'deal_eid'		=> $params['deal_eid'],
        		'deal_name'		=> $deal_name,
        		'charge_eid'	=> $params['charge_eid'],
        	);
        	if( $params['pass'] ){
        		$salt = getRandStr(10);
        		$data['pass'] = encryptPass($params['pass'], $salt);
        		$data['salt'] = $salt;
        	}
    		$this->mBase->update('vip_users',$data,array('id'=>$params['id']));
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
	}
	
	public function log_login(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('page','size');
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		//get users
		$condition = array();
		$res = $this->mBase->getList('vip_users_log_login',$condition,'*','id DESC',$page,$size);
		$data['results'] = $res->results;
		$data['pager'] = $res->pager;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'log_login';
		$this->_view('common/header', $tags);
		$this->_view('vip/log_login_list', $data);
		$this->_view('common/footer');
	}

    public function getUserByCompany()
    {
        $ret = array('err_no'=>1000,'err_msg'=>'系统错误');
        $company_id = $this->input->get('company_id');
        if(!empty($company_id))
        {
            $condition['AND'][] = "`company_id`={$company_id}";
            $condition['AND'][] = "`is_del`=0";
            $info_list = $this->mBase->getList('vip_users', $condition);
            if(count($info_list) == 0){
                $ret = array('err_no'=>100,'err_msg'=>'无所属用户，请添加！');
            }
            else{
                $ret = array('err_no'=>0,'err_msg'=>'操作成功', 'info_list' => $info_list);
            }
        }
        $this->output($ret);
    }
}
