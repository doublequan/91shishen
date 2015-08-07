<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class user extends Base 
{
	private $active_top_tag = 'stat';

	public function __construct(){
		parent::__construct();
	}

    public function index(){
    }
    
    public function amount(){
    	$data = array();
    	//init parameters
    	$must = array();
    	$fields = array('act','start','end');
    	$this->checkParams('get',$must,$fields);
    	$params = $data['params'] = $this->params;
    	$t = time();
    	$t = $t-$t%3600;
    	$start = $params['start'] ? strtotime($params['start']) : $t-86400*7;
    	$end = $params['end'] ? strtotime($params['end']) : $t;
    	//get data
    	$os_types = $data['os_types'] = getConfig('os_types');
    	$data['xAxis'] = $data['series'] = $data['results'] = array();
    	$condition = array(
    		'AND' => array('t>='.$start,'t<'.($end+86400)),
    	);
    	$res = $this->mBase->getList('stat_users',$condition,'*','t ASC');
    	if( $res ){
    		foreach ( $res as $row ){
    			$t = date('m-d',$row['t']);
    			//create xAxis
    			$data['xAxis'][] = $t;
    			//create results
    			if( isset($data['results'][$t][$row['os']]) ){
    				$data['results'][$t][$row['os']]['change'] += $row['change'];
    			} else {
    				$data['results'][$t][$row['os']] = array(
    					'change'	=> $row['change'],
    					'total'		=> $row['total'],
    				);
    			}
    		}
    		//create series
    		foreach ( $data['results'] as $day=>$oss ){
    			foreach( $oss as $os=>$row ){
		    		if( isset($data['series'][$os]) ){
		    			$data['series'][$os]['data'][] = intval($row['change']);
		    		} else {
		    			$data['series'][$os] = array(
		    				'name' => $os_types[$os],
		    				'data' => array(intval($row['change'])),
		    			);
		    		}
    			}
    		}
    	}
    	$data['xAxis'] = array_unique($data['xAxis']);
    	sort($data['xAxis']);
    	$data['params']['start'] = date('Y-m-d',$start);
    	$data['params']['end'] = date('Y-m-d',$end);
    	//get cached stores
    	$tags['active_top_tag'] = $this->active_top_tag;
    	$tags['active_menu_tag'] = 'user_amount';
    	$this->_view('common/header', $tags);
    	$this->_view('stat/user_amount', $data);
    	$this->_view('common/footer');
    }
    
    public function stat(){
    	$data = array();
    	//init parameters
    	$must = array();
    	/**
    	 * @type=1	是否有会员卡
    	 * @type=2	会员分组
    	 * @type=3	注册系统分布
    	 * @type=4	最后登陆系统分布
    	 */
    	$types = array(
    		1 => '持卡用户统计',
    		2 => '会员分组统计',
    		3 => '注册来源统计',
    		4 => '活跃来源统计',
    	);
    	$fields = array('type');
    	$this->checkParams('get',$must,$fields);
    	$this->params['type'] = $this->params['type']=='' ? -1 : intval($this->params['type']);
    	$this->params['type'] = in_array($this->params['type'], array(1,2,3,4)) ? $this->params['type'] : 1;
    	$params = $data['params'] = $this->params;
    	$type = $params['type'];
    	//get data
    	$data['results'] = $data['head'] = array();
    	$data['chart_title'] = $types[$type];
    	if( $type==1 ){
    		$a = $this->mBase->getCount('users',array('AND'=>array('status=1',"cardno<>''")));
    		$b = $this->mBase->getCount('users',array('AND'=>array('status=1',"cardno=''")));
    		$total = $a+$b;
    		$pa = round(($a/$total)*100,2);
    		$pb = round(($b/$total)*100,2);
    		//create results
    		$data['head'] = array('会员类型','数量','占比');
    		$data['results'] = array(
    			array(
    				'name'		=> '储值卡用户数',
    				'number'	=> $a,
    				'percent'	=> $pa,
    			),
    			array(
    				'name'		=> '非储值卡用户数',
    				'number'	=> $b,
    				'percent'	=> $pb,
    			),
    		);
    		//create series
    		$data['series'] = array(
    			array(
    				'name'		=> '储值卡用户数',
    				'y'			=> $pa,
    				'sliced'	=> false,
    				'selected'	=> true,
    			),
    			array(
    				'name'		=> '非储值卡用户数',
    				'y'			=> $pb,
    				'sliced'	=> false,
    				'selected'	=> false,
    			),
    		);
    	} elseif ( $type==2 ){
    		$groups = $this->getCacheList('users_group',array('AND'=>array('is_del=0')),'USERS_GROUP_IS_DEL_0',600);
    		if( $groups ){
    			$results = array();
    			foreach ( $groups as $row ){
    				$condition = array('AND'=>array('status=1','group_id='.$row['id']));
    				$results[$row['id']] = $this->mBase->getCount('users',$condition);
    			}
    			$total = array_sum($results);
    			arsort($results);
    			$data['head'] = array('用户组','数量','占比');
    			foreach ( $results as $group_id=>$v ){
    				$per = round(($v/$total)*100,2);
    				$data['results'][] = array(
    					isset($groups[$group_id]) ? $groups[$group_id]['name'] : '未知分组',
    					$v,
    					$per
    				);
    				$data['series'][] = array(
    					'name'		=> isset($groups[$group_id]) ? $groups[$group_id]['name'] : '未知分组',
    					'y'			=> $per,
    					'sliced'	=> false,
    					'selected'	=> false,
    				);
    			}
    			@$data['series'][0]['selected'] = true;
    		}
    	} elseif ( $type==3 ){
    		$os_types = getConfig('os_types');
    		if( $os_types ){
    			$results = array();
    			foreach ( $os_types as $os=>$v ){
    				$condition = array('AND'=>array('status=1','create_os='.$os));
    				$results[$os] = $this->mBase->getCount('users',$condition);
    			}
    			$total = array_sum($results);
    			arsort($results);
    			$data['head'] = array('系统','数量','占比');
    			foreach ( $results as $os=>$v ){
    				$per = round(($v/$total)*100,2);
    				$data['results'][] = array(
    					isset($os_types[$os]) ? $os_types[$os] : '未知系统',
    					$v,
    					$per
    				);
    				$data['series'][] = array(
    					'name'		=> isset($os_types[$os]) ? $os_types[$os] : '未知系统',
    					'y'			=> $per,
    					'sliced'	=> false,
    					'selected'	=> false,
    				);
    			}
    			@$data['series'][0]['selected'] = true;
    		}
    	} elseif ( $type==4 ){
    	$os_types = getConfig('os_types');
    		if( $os_types ){
    			$results = array();
    			foreach ( $os_types as $os=>$v ){
    				$condition = array('AND'=>array('status=1','login_os='.$os));
    				$results[$os] = $this->mBase->getCount('users',$condition);
    			}
    			$total = array_sum($results);
    			arsort($results);
    			$data['head'] = array('系统','数量','占比');
    			foreach ( $results as $os=>$v ){
    				$per = round(($v/$total)*100,2);
    				$data['results'][] = array(
    					isset($os_types[$os]) ? $os_types[$os] : '未知系统',
    					$v,
    					$per
    				);
    				$data['series'][] = array(
    					'name'		=> isset($os_types[$os]) ? $os_types[$os] : '未知系统',
    					'y'			=> $per,
    					'sliced'	=> false,
    					'selected'	=> false,
    				);
    			}
    			@$data['series'][0]['selected'] = true;
    		}
    	}
    	//common data
    	$data['types'] = $types;
    	//get cached stores
    	$tags['active_top_tag'] = $this->active_top_tag;
    	$tags['active_menu_tag'] = 'user_stat';
    	$this->_view('common/header', $tags);
    	$this->_view('stat/user_stat', $data);
    	$this->_view('common/footer');
    }
}