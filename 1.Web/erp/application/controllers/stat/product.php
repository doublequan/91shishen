<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/../base.php';

class product extends Base 
{
	private $active_top_tag;
    private $sites;
	private $favTypes;

	public function __construct(){
		parent::__construct();

        $this->active_top_tag = 'stat';
		$res = $this->mBase->getList('sites',array('AND'=>array('is_del=0')));
		if( $res ){
			foreach ( $res as $row ){
				$this->sites[$row['id']] = $row;
			}
		}
	}

    public function index(){
    }

    public function pro_fav_total(){
        $data = array();
        //init parameters
        $must = array();
        $fields = array('site_id','day');
        $this->checkParams('get',$must,$fields);
        $params = $this->params;

        if(empty($params['site_id'])){
            $params['site_id'] = current(array_keys($this->sites));
        }
        if(empty($params['day'])){
            $params['day'] = date('Y-m-d', strtotime('-1 day'));
        }
        $data['params'] = $params;
        
        //get condition
        $condition = array(
            'AND' => array('site_id='.$params['site_id']),
        );
        $day = $params['day'];
        if( $day ){
            $condition['AND'][] = "day='{$day}'";
        }

        $res = $this->mBase->getList('stat_product_fav',$condition, '*' , 'create_time DESC');
        $results = $chart_data_name = $chart_data_value = array();
        if(is_array($res) && count($res) > 0){
            $res = current($res);
            
            $res_info = json_decode($res['info'], true);
            foreach ($res_info as $key => $value) {
                $results[] = array(
                    'product_id'=> $key,
                    'sku'    => $value['sku'],
                    'name'    => $value['name'],
                    'number'  => $value['number'],
                );
                $chart_data_name[] = $value['name'];
                $chart_data_value[] = $value['number'];
            }
        }
        $data['results'] = $results;
        $data['chart_data_name'] = json_encode($chart_data_name);
        $data['chart_data_value'] = json_encode($chart_data_value);

        //Common Data
        $data['sites'] = $this->sites;
        //display templates
        $tags['active_top_tag'] = $this->active_top_tag;
        $tags['active_menu_tag'] = 'pro_fav_total';
        $this->_view('common/header', $tags);
        $this->_view('stat/pro_fav_total', $data);
        $this->_view('common/footer');
    }
}
