<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class area extends Base {
	private $active_top_tag = 'system';

	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$data = array();
		//init parameters
		$must = array();
		$fields = array('disable');
		$this->checkParams('get',$must,$fields);
		$params = $this->params;

		$used_citys = $this->mBase->getList('areas', array('AND'=>array('disable=0', 'deep=3')),'id');
		$city_ids = array();
		foreach ($used_citys as $value) {
			$city_ids[] = intval($value['id']);
		}
		$city_ids = implode(',', $city_ids);

		$res = array();
		if(empty($params['disable'])){
			$params['disable'] = 0;
			$condition = array(
				'OR' => array(array('(deep<4 and disable=0)',"deep=4 and father_id in({$city_ids})")),
			);
			$res = $this->mBase->getList('areas',$condition, '*', 'sort ASC');
		}
		else{
			$params['disable'] = 1;
			$res = $this->mBase->getList('areas',array(), '*', 'sort ASC');
		}
		$data['params'] = $params;

		if(!empty($res)){
			$res = getTreeFromArray($res);
		}
		$data['results'] = $res;

		$this->load->model('area_model', 'area');
		$province_res = $this->area->getProvinceList();
		foreach ($province_res as $row) {
			$data['province_list'][$row['id']] = $row;
		}
		$city_res = $this->area->getCityList();
		foreach ($city_res as $row) {
			$data['city_list'][$row['id']] = $row;
		}

		$tags['active_top_tag'] = $this->active_top_tag;
		$tags['active_menu_tag'] = 'area';
		$this->_view('common/header', $tags);
		$this->_view('system/area_list', $data);
		$this->_view('common/footer');
	}
	
	public function street_add() {
		$data = array();
		$data['province_list'] = $this->mBase->getList('areas', array('AND' => array('disable=0','deep=1')));
		
		//display templates
		$this->_view('system/street_add', $data);
	}
	
	public function street_edit() {
		$data = array();
		//get single group
		$data['single'] = array();
		$id = intval($this->input->get('id'));
		$single = $this->mBase->getSingle('areas','id',$id);
		$district = $this->mBase->getSingle('areas','id',$single['father_id']);
		$city = $this->mBase->getSingle('areas','id',$district['father_id']);

		$data['single'] = $single;
		$data['district'] = $district;
		$data['city'] = $city;

		//display templates
		$this->_view('system/street_edit', $data);
	}
	
	public function doStreetAdd() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('district','street');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$father_id = intval($params['district']);
			//check parameters
			if( $father_id ){
				$t = $this->mBase->getSingle('areas','id',$father_id);
				if( !$t ){
	        		$ret = array('err_no'=>1000,'err_msg'=>'上级区县不存在');
	        		break;
	        	}
			}
			$t = $this->mBase->getSingle('areas','name',$params['street']);
			if( $t && $t['father_id'] == $father_id){
				$ret = array('err_no'=>1000,'err_msg'=>'此区域已经存在相同名称街道');
				break;
			}

			$sql = "SELECT MAX(id) FROM areas WHERE father_id={$father_id}";
			$q = $this->db->query($sql);
			$q = $q->first_row('array');
			$q = $q["MAX(id)"];

			$new_id = intval($father_id.'001');
			if($q){
				$new_id = intval($q);
				$new_id++;
			}

			//insert data
			$data = array(
				'id' 		=> $new_id,
				'father_id' => $father_id,
				'deep'      => 4,
				'name'      => $params['street'],
			);
			$this->mBase->insert('areas',$data);
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}
	
	public function doStreetEdit() {
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
		do{
			//get parameters
			$must = array('id','street');
			$fields = array();
			$this->checkParams('post',$must,$fields);
			$params = $this->params;
			$id = intval($params['id']);
			//check parameters
			$street = $this->mBase->getSingle('areas','id',$id);
			if( !$street ){
				$ret = array('err_no'=>1000,'err_msg'=>'街道不存在');
				break;
			}
			
			$t = $this->mBase->getSingle('areas','name',$params['street']);
			if( $t && $t['father_id'] == $street['father_id']){
				$ret = array('err_no'=>1000,'err_msg'=>'此区域已经存在相同名称街道');
				break;
			}

			//update data
			$data = array(
				'name'				=> $params['street'],
			);
			$this->mBase->update('areas',$data,array('id'=>$id));
			$ret = array('err_no'=>0,'err_msg'=>'操作成功');
		} while(0);
		$this->output($ret);
	}

	public function close_city()
	{
		$category_id = $this->input->post('category_id');
		$single_info = $this->mBase->getSingle('areas', 'id', $category_id);
		if(empty($single_info)){
			die('{"error":1, "msg": "信息错误，请刷新页面重试！"}');
		}

		$father_id = $single_info['father_id'];

		$tmp_citys = $this->mBase->getList('areas', array('AND'=>array("father_id={$father_id}", "disable=0")));

		$sql = "UPDATE areas SET disable=1 WHERE id={$category_id} OR father_id={$category_id}";
		if(is_array($tmp_citys) && count($tmp_citys) == 1){
			$sql .= " OR id={$father_id}";
		}

		$this->db->query($sql);
		die('{"error":0, "msg": "'.$this->db->affected_rows().'"}');
	}

	public function add_city()
	{
		$prov = $this->input->post('prov');
		$city = $this->input->post('city');
		$single_info = $this->mBase->getSingle('areas', 'id', $city);
		if(empty($single_info)){
			die('{"error":1, "msg": "信息错误，请刷新页面重试！"}');
		}

		$sql = "UPDATE areas SET disable=0 WHERE id={$city} OR id={$prov} OR father_id={$city}";
		$this->db->query($sql);
		die('{"error":0, "msg": "'.$this->db->affected_rows().'"}');
	}

	public function delete()
	{
		$category_id = $this->input->get('category_id');
		if(empty($category_id)){
			die('{"error":1, "msg": "信息错误，请刷新页面重试！"}');
		}
		$sql = "DELETE FROM areas WHERE id={$category_id} AND deep=4";
		$this->db->query($sql);
		die('{"error":0, "msg": "'.$this->db->affected_rows().'"}');
	}

	public function updateSort(){
		$ret = array('err_no'=>1000,'err_msg'=>'系统错误');
    	do{
        	//get parameters
        	$must = array('category_id','category_sort');
        	$fields = array();
        	$this->checkParams('post',$must,$fields);
        	$params = $this->params;
        	$category_id = intval($params['category_id']);
        	//check parameters
        	$single = $this->mBase->getSingle('areas','id',$category_id);
        	if( !$single ){
        		$ret = array('err_no'=>1000,'err_msg'=>'good category is not exists');
        		break;
        	}
        	
        	$data = array(
        		'sort' => intval($params['category_sort']),
        	);
    		$this->mBase->update('areas',$data,array('id'=>$category_id));
    		$ret = array('err_no'=>0,'err_msg'=>'操作成功');
    	} while(0);
    	$this->output($ret);
	}
}