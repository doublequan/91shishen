<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class user extends Base 
{
	private $active_top_tag = 'user';
	
	//Common Groups
	private $groups = array();
	
	public $statusMap = array(
		1	=> '正常用户',
		2	=> '已删除用户',
		3	=> '已锁定用户',
	);
	
	private $os_types;

	public function __construct(){
		parent::__construct();
		//get user_groups
		$res = $this->getCacheList('users_group',array('AND'=>array('is_del=0')),'USERS_GROUP_IS_DEL_0',600);
		if( $res ){
			foreach ( $res as $row ){
				$this->groups[$row['id']] = $row;
			}
		}
		$this->os_types = getConfig('os_types');
	}

	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('status','keyword','os','mobile_status','email_status','create_time_start','create_time_end','page','size');
		$this->checkParams('get',$must,$fields);
		if( !$this->params['status'] || !array_key_exists($this->params['status'], $this->statusMap) ){
			$this->params['status'] = 1;
		}
		$this->params['os'] = $this->params['os']=='' ? -1 : intval($this->params['os']);
		$this->params['mobile_status'] = $this->params['mobile_status']=='' ? -1 : intval($this->params['mobile_status']);
		$this->params['email_status'] = $this->params['email_status']=='' ? -1 : intval($this->params['email_status']);
		$params = $data['params'] = $this->params;
		$page = max(1,intval($params['page']));
		$size = max(20,min(100,intval($params['size'])));
		$data['url'] = http_build_query($params);
		//get users
		$condition = array();
		$status = intval($params['status']);
		if( $status ){
			$condition['AND'][] = 'status='.$status;
		}
		$k = $params['keyword'];
		if( $k ){
			$condition['OR'][0] = array(
				"username LIKE '%".$k."%'",
				"email LIKE '%".$k."%'",
				"mobile LIKE '%".$k."%'",
				"cardno LIKE '%".$k."%'",
			);
		}
		if( $params['os']!=-1 ){
			$condition['AND'][] = 'create_os='.$params['os'];
		}
		if( $params['mobile_status']!=-1 ){
			$condition['AND'][] = 'mobile_status='.$params['mobile_status'];
		}
		if( $params['email_status']!=-1 ){
			$condition['AND'][] = 'email_status='.$params['email_status'];
		}
		$create_time_start = $params['create_time_start']=='' ? 0 : strtotime($params['create_time_start']);
		if( $create_time_start ){
			$condition['AND'][] = 'create_time>='.$create_time_start;
		}
		$create_time_end = $params['create_time_end']=='' ? 0 : strtotime($params['create_time_end']);
		if( $create_time_end ){
			$condition['AND'][] = 'create_time<='.$create_time_end;
		}
		$res = $this->mBase->getList('users',$condition,'*','id DESC',$page,$size);
		$outRes = $this->mBase->getList('users',$condition,'*','id DESC',0,0);
		if( $res->results ){
			foreach( $res->results as &$row ){
				$row['uname'] = $row['username'];
				if( $k ){
					$row['username'] = str_replace($k, '<font color="red">'.$k.'</font>', $row['username']);
					$row['email'] = str_replace($k, '<font color="red">'.$k.'</font>', $row['email']);
					$row['mobile'] = str_replace($k, '<font color="red">'.$k.'</font>', $row['mobile']);
					$row['cardno'] = str_replace($k, '<font color="red">'.$k.'</font>', $row['cardno']);
				}
			}
			unset($row);
		}
		$data['results'] = $res->results;
		$_SESSION['outdata']=$outRes;	
		$data['pager'] = $res->pager;
		//Common Data
		$data['statusMap'] = $this->statusMap;
		$data['groups'] = $this->groups;
		$data['os_types'] = $this->os_types;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'user';
		$this->_view('common/header', $tags);
		$this->_view('user/user_list', $data);
		$this->_view('common/footer');
	}
	
	public function add(){
		$data = array();
		//Common Data
		$data['groups'] = $this->groups;
		$data['os_types'] = $this->os_types;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'user_add';
		$this->_view('user/user_add', $data);
	}
	
	public function edit(){
		$data = array();
		//get parameters
		$must = array('id');
		$fields = array();
		$this->checkParams('get',$must,$fields);
		$params = $data['params'] = $this->params;
		$id = intval($params['id']);
		//get user
		$data['single'] = $this->mBase->getSingle('users','id',$id);
		//Common Data
		$data['groups'] = $this->groups;
		$data['os_types'] = $this->os_types;
		//Config Data
		$data['invoice_types'] = getConfig('invoice_types');
		//get user's store
		$data['store'] = array();
		if( $data['single']['store_id'] ){
			$data['store'] = $this->mBase->getSingle('stores','id',$data['single']['store_id']);
		}
		//get all undisabled provinces and cities
		$data['provs'] = $data['citys'] = array();
		$condition = array(
			'AND' => array('disable=0'),
		);
		$res = $this->mBase->getList('areas',$condition,'*','sort ASC');
		if( $res ){
			foreach ( $res as $row ){
				if( $row['deep']==1 ){
					$data['provs'][] = $row;
				} elseif ( $row['deep']==2 ){
					$data['citys'][$row['father_id']][] = $row;
				}
			}
		}
		//get all stores
		$data['stores'] = array();
		$condition = array(
				'AND' => array('is_del=0'),
		);
		$res = $this->mBase->getList('stores',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$data['stores'][$row['city']][] = $row;
			}
		}
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'product_edit';
		$this->_view('user/user_edit', $data);
	}
	
	public function import(){
		$data = array();
		//Common Data
		$data['groups'] = $this->groups;
		//display templates
		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'user_import';
		$this->_view('user/user_import', $data);
	}
	
	public function doAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
        do{
        	//get parameters
        	$must = array('username','email','mobile','pass','repass');
        	$fields = array('group_id','cardno','discount');
        	$this->checkParams('post',$must,$fields);
        	if( !array_key_exists($this->params['group_id'],$this->groups) ){
        		$this->params['group_id'] = current(array_keys($this->groups));
        	}
        	$params = $this->params;
        	//check parameters
        	$params['username'] = strtolower($params['username']);
        	if( !preg_match('/^[0-9a-z]{6,20}$/',$params['username']) ){
        		$ret = array('err_no'=>3001,'err_msg'=>'用户名格式错误');
        		break;
        	}
        	if( !filter_var($params['email'],FILTER_VALIDATE_EMAIL) ){
        		$ret = array('err_no'=>3001,'err_msg'=>'用户邮箱格式错误');
        		break;
        	}
        	if( !preg_match('/^1[\d]{10}$/',$params['mobile']) ){
        		$ret = array('err_no'=>3002,'err_msg'=>'用户手机号码格式错误');
        		break;
        	}
        	if( strlen($params['pass'])<6 ){
        		$ret = array('err_no'=>3003,'err_msg'=>'登陆密码格式错误');
        		break;
        	}
        	if( $params['pass'] != $params['repass'] ){
        		$ret = array('err_no'=>3004,'err_msg'=>'两次输入的密码不一致');
        		break;
        	}
        	$user = $this->mBase->getSingle('users','username',$params['username']);
        	if( $user ){
        		$ret = array('err_no'=>3005,'err_msg'=>'用户名已经存在');
        		break;
        	}
        	$user = $this->mBase->getSingle('users','mobile',$params['mobile']);
        	if( $user ){
        		$ret = array('err_no'=>3006,'err_msg'=>'手机号码已经存在');
        		break;
        	}
        	$ip = getUserIP();
        	$time = time();
        	$salt = getRandStr(10);
        	$data = array(
        		'group_id'		=> $params['group_id'],
        		'username'		=> $params['username'],
        		'email'			=> $params['email'],
        		'mobile'		=> $params['mobile'],
        		'pass'			=> encryptPass($params['pass'], $salt),
        		'salt'			=> $salt,
        		'status'		=> 1,
        		'login_ip'		=> $ip,
        		'login_time'	=> $time,
        		'create_ip'		=> $ip,
        		'create_time'	=> $time,
        		'cardno'		=> $params['cardno'],
        		'status'		=> 1,
        	);
            $user = $this->mBase->insert('users',$data);
            if( !$user ){
                $ret = array('err_no'=>3004,'err_msg'=>'添加用户失败');
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
    		$must = array('id');
    		$fields = array('pass','repass','group_id','birthday','cardno','discount','gender','tel','qq','receipt_title','receipt_des','store_id');
    		$this->checkParams('post',$must,$fields);
    		$params = $this->params;
    		//update userinfo
    		$data = array(
    			'group_id'		=> $params['group_id'],
    			'store_id'		=> intval($params['store_id']),
    			'birthday'		=> date('Y-m-d',strtotime($params['birthday'])),
    			'cardno'		=> $params['cardno'],
    			'discount'		=> max(1,min(100,$params['discount'])),
    			'gender'		=> $params['gender'],
    			'tel'			=> $params['tel'],
    			'qq'			=> $params['qq'],
    			'receipt_title'	=> $params['receipt_title'],
    			'receipt_des'	=> $params['receipt_des'],
    		);
    		if( $params['pass'] ){
    			if( strlen($params['pass'])<6 ){
    				$ret = array('err_no'=>1000,'err_msg'=>'新密码不能少于6位');
    				break;
    			}
    			if( $params['pass']!=$params['repass'] ){
    				$ret = array('err_no'=>1000,'err_msg'=>'两次输入的密码不一致');
    				break;
    			}
    			$data['pass'] = md5($params['pass']);
    		}
    		$this->mBase->update('users',$data,array('id'=>$params['id']));
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
	}
	
	public function doEditStatus(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
    	do{
    		//get parameters
    		$must = array('id','status');
    		$fields = array();
    		$this->checkParams('get',$must,$fields);
    		$params = $this->params;
    		//update userinfo
    		$data = array(
    			'status' => $params['status'],
    		);
    		$rows = $this->mBase->update('users',$data,array('id'=>$params['id']));
    		if( $rows ){
    			//remove user session
    			$this->mBase->update('users_session',array('id'=>$params['id']));
    		}
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
	}
	
	public function doEditStatusMulti(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('ids','status');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$status = intval($params['status']);
			$status = in_array($status, array(2,3)) ? $status : 1;
			//update userinfo
			foreach ( explode(',', $params['ids']) as $id ){
				$id = intval($id);
				if( $id ){
					$data = array(
						'status' => $status,
					);
					$rows = $this->mBase->update('users',$data,array('id'=>$id));
					if( $rows ){
						$this->mBase->delete('users_session',array('id'=>$id),true);
					}
				}
			}
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}

    public function export() {
        $outdata=array();
        isset($_SESSION['outdata'])?($outdata=$_SESSION['outdata']):($outdata=array());
        if(!empty($outdata)){
            foreach ($outdata as $k => $v) {
               $outData[$k][] = $k+1;  
               $outData[$k][] =$v['username']?$v['username']:'';
               $outData[$k][] = $v['gender']=='1'?'男':'女';   
               $outData[$k][] = $v['mobile']?'\''.$v['mobile']:'';                
               $outData[$k][] = $v['birthday']?$v['birthday']:'';
               $outData[$k][] = $v['email']?$v['email']:'';
               $outData[$k][] = $v['cardno']?'\''.$v['cardno']:'';
               $address='';
               $addr=$this->mBase->getSingle('users_address','uid',$v['id']);
               $address=isset($addr['address'])?$addr['address']:'';
               $outData[$k][] = $address?$address:'';
               $os_type_name='';
               if(isset($v['os_type'])){
			      $os_type_name =$v['os_type']=='0'?'Web':($v['os_type']=='1'?'Android':($v['os_type']=='2'?'iPhone':($v['os_type']=='3'?'iPad':($v['os_type']=='4'?'Touch':'未知'))));
               }
               $outData[$k][] =$os_type_name;
			   $outData[$k][] =$v['create_ip']>0?long2ip($v['create_ip']):0;
               $outData[$k][] = $v['create_time']?date('Y-m-d H:i:s',$v['create_time']):'';
               $outData[$k][] =$v['login_ip']>0?long2ip($v['login_ip']):0;
               $outData[$k][] = $v['login_time']?date('Y-m-d H:i:s',$v['login_time']):'';
            }
            $header = array('序号', '用户名', '性别', '手机号码','生日','登陆邮箱', '会员卡号','地址','注册来源','注册IP','注册时间','登录IP','最后登录时间');
            $this->exportExcel($header, $outData);               
        }else{
            $url=site_url('user/user');
            echo "<script language='javascript' type='text/javascript'>alert('没有数据无法导出');window.location.href='$url';</script>";            
        }
    }	
}
