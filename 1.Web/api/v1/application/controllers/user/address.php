<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class address extends Base {
	
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model','modUser');
    }
    
    public function lists(){
        $ret = array('err_no'=>1000,'err_msg'=>'system error');
        do{
        	$this->checkParams('post');
        	$params = $this->params;
        	//get user
        	$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
        	if( !($user && $user['uid']) ){
        		$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
        		break;
        	}
        	$uid = $user['uid'];
            //update userinfo
            $condition = array(
            	'AND' => array('uid='.$uid),
            );
            $list = $this->modUser->getList('users_address',$condition,'*','id DESC');
            if( $list ){
            	$data['areas'] = array();
            	foreach( $list as &$row ){
            		unset($row['uid'],$row['is_self'],$row['create_time']);
            		$arr = array('prov','city','district','street');
            		foreach( $arr as $v ){
            			if( !isset($data['areas'][$row[$v]]) ){
            				$cache_key = 'AREA_'.$row[$v];
            				$t = Common_Cache::get($cache_key);
            				if( !$t ){
            					$t = $this->modUser->getSingle('areas','id',$row[$v]);
            					Common_Cache::save($cache_key, $t, 86400);
            				}
            				if( $t ){
            					$data['areas'][$t['id']] = $t['name'];
            				} else {
            					$data['areas'][0] = '';
            				}
            			}
            			$row[$v.'_name'] = $data['areas'][$row[$v]];
            		}
            	}
            	unset($row);
            }
            $ret = array(
            	'err_no'  =>0,
            	'err_msg' =>'success',
            	'results' => array(
            		'list'  => $list,
            	),
            );
        } while(0);
        $this->output($ret);
    }
    
    public function add(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('is_default','prov','city','district','street','zip','address','receiver');
    		$fields = array('tel','mobile');
    		$this->checkParams('post',$must,$fields);
    		$params = $this->params;
    		//get user
    		$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
        	if( !($user && $user['uid']) ){
        		$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
        		break;
        	}
        	$uid = $user['uid'];
        	//check address count
        	if( $this->modUser->getAddressCount($uid)>=10 ){
        		$ret = array('err_no'=>3002,'err_msg'=>'already has 10 addresses');
        		break;
        	}
    		//check tel
    		if( $params['tel']=='' && $params['mobile']=='' ){
    			$ret = array('err_no'=>3003,'err_msg'=>'both tel and mobile are empty');
    			break;
    		}
    		//check area
    		$arr = array('prov','city','district','street');
    		$flag = true;
    		foreach ( $arr as $v ){
    			$t = $this->modUser->getSingle('areas','id',$params[$v]);
    			if( !$t ){
    				$flag = false;
    				break;
    			}
    		}
    		if( !$flag ){
    			$ret = array('err_no'=>3004,'err_msg'=>$v.' is not exists');
    			break;
    		}
    		//update user address default set
    		if( $params['is_default']==1 ){
    			$this->modUser->update('users_address',array('is_default'=>0),array('uid'=>$uid));
    		}
    		//add user address
    		$data = array(
    			'uid'			=> $uid,
    			'is_default'	=> $params['is_default'],
    			'prov'			=> $params['prov'],
    			'city'			=> $params['city'],
    			'district'		=> $params['district'],
    			'street'		=> $params['street'],
    			'zip'			=> $params['zip'],
    			'address'		=> $params['address'],
    			'receiver'		=> $params['receiver'],
    			'tel'			=> $params['tel'],
    			'mobile'		=> $params['mobile'],
    			'create_time'	=> time(),
    		);
    		$this->modUser->insert('users_address',$data);
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>array());
    	} while(0);
    	$this->output($ret);
    }
    
    public function modify(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('id','is_default','prov','city','district','street','zip','address','receiver');
    		$fields = array('tel','mobile');
    		$this->checkParams('post',$must,$fields);
    		$params = $this->params;
    		//get user
    		$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
    		if( !($user && $user['uid']) ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
    			break;
    		}
    		$uid = $user['uid'];
    		//get address
    		$address = $this->modUser->getSingle('users_address','id',$params['id'],'uid,is_default');
    		if( !$address ){
    			$ret = array('err_no'=>3002,'err_msg'=>'address is not exists');
    			break;
    		}
    		if( $address['uid']!=$uid ){
    			$ret = array('err_no'=>3003,'err_msg'=>'address and user not match');
    			break;
    		}
    		//check tel
    		if( $params['tel']=='' && $params['mobile']=='' ){
    			$ret = array('err_no'=>3004,'err_msg'=>'both tel and mobile are empty');
    			break;
    		}
    		//check area
    		$arr = array('prov','city','district','street');
    		$flag = true;
    		foreach ( $arr as $v ){
    			$t = $this->modUser->getSingle('areas','id',$params[$v]);
    			if( !$t ){
    				$flag = false;
    				break;
    			}
    		}
    		if( !$flag ){
    			$ret = array('err_no'=>3005,'err_msg'=>$v.' is not exists');
    			break;
    		}
    		//update user address default set
    		if( $params['is_default']==1 && $address['is_default']==0 ){
    			$this->modUser->update('users_address',array('is_default'=>0),array('uid'=>$uid));
    		}
    		//add user address
    		$data = array(
    			'uid'			=> $uid,
    			'is_default'	=> $params['is_default'],
    			'prov'			=> $params['prov'],
    			'city'			=> $params['city'],
    			'district'		=> $params['district'],
    			'street'		=> $params['street'],
    			'zip'			=> $params['zip'],
    			'address'		=> $params['address'],
    			'receiver'		=> $params['receiver'],
    			'tel'			=> $params['tel'],
    			'mobile'		=> $params['mobile'],
    		);
    		$this->modUser->update('users_address',$data,array('id'=>$params['id']));
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>array());
    	} while(0);
    	$this->output($ret);
    }
    
    public function remove(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('id');
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//get user
    		$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
    		if( !($user && $user['uid']) ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
    			break;
    		}
    		$uid = $user['uid'];
    		//get address
    		$address = $this->modUser->getSingle('users_address','id',$params['id'],'uid,is_default');
    		if( !$address ){
    			$ret = array('err_no'=>3002,'err_msg'=>'address is not exists');
    			break;
    		}
    		if( $address['uid']!=$uid ){
    			$ret = array('err_no'=>3003,'err_msg'=>'address and user not match');
    			break;
    		}
    		//delete address
    		$this->modUser->delete('users_address',array('id'=>$params['id']),true);
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>array());
    	} while(0);
    	$this->output($ret);
    }
    
    public function setdefault(){
    	$ret = array('err_no'=>1000,'err_msg'=>'system error');
    	do{
    		//get parameters
    		$must = array('id');
    		$this->checkParams('post',$must);
    		$params = $this->params;
    		//get user
    		$user = $this->modUser->getSingle('users_session','sessionid',$params['sessionid'],'uid');
    		if( !($user && $user['uid']) ){
    			$ret = array('err_no'=>3001,'err_msg'=>'user is not login');
    			break;
    		}
    		$uid = $user['uid'];
    		//get address
    		$address = $this->modUser->getSingle('users_address','id',$params['id'],'uid,is_default');
    		if( !$address ){
    			$ret = array('err_no'=>3002,'err_msg'=>'address is not exists');
    			break;
    		}
    		if( $address['uid']!=$uid ){
    			$ret = array('err_no'=>3003,'err_msg'=>'address and user not match');
    			break;
    		}
    		if( $address['is_default']!=1 ){
    			//update user address default set
    			$this->modUser->update('users_address',array('is_default'=>0),array('uid'=>$uid));
    			$this->modUser->update('users_address',array('is_default'=>1),array('id'=>$params['id']));
    		}
    		$ret = array('err_no'=>0,'err_msg'=>'success','results'=>array());
    	} while(0);
    	$this->output($ret);
    }
}