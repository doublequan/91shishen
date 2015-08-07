<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__).'/base.php';

class special extends Base{
	
	public function detail( $alias ){
		$data = array();
		$alias = trim($alias);
		if( !preg_match('/^[a-zA-Z0-9]+$/', $alias) ){
			show_404();
			return;
		}
		$data['single'] = $this->Base_model->getSingle('specials','alias',$alias);
		if( !$data['single'] || $data['single']['is_del']==1 ){
			show_404();
			return;
		}
		//check site_id
		$site_id = isset($_COOKIE['SITEID']) ? intval($_COOKIE['SITEID']) : 1;
		$sites = array();
		$condition = array(
			'AND' => array('special_id='.$data['single']['id']),
		);
		$res = $this->Base_model->getList('specials_site',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$sites[$row['site_id']] = $row;
			}
		}
		if( !isset($sites[$site_id]) ){
			show_404();
			return;
		}
		//get special products
		$this->load->model('Product_model', 'mProduct');
		$data['products'] = $this->mProduct->getSpecialProducts($data['single']['id']);
		$this->load->view('special/'.$alias, $data);
	}
	
	public function free(){
		$method = 'free';
		$data = array();
		$data['single'] = $this->Base_model->getSingle('specials','alias',$method);
		if( !$data['single'] ){
			show_404();
			return;
		}
		//check site_id
		$site_id = isset($_COOKIE['SITEID']) ? intval($_COOKIE['SITEID']) : 1;
		$sites = array();
		$condition = array(
			'AND' => array('special_id='.$data['single']['id']),
		);
		$res = $this->Base_model->getList('specials_site',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$sites[$row['site_id']] = $row;
			}
		}
		if( !isset($sites[$site_id]) ){
			show_404();
			return;
		}
		//get userInfo
		$userInfo = $this->session->userdata('userinfo');
		//get special products
		$data['products'] = array();
		$this->load->model('Product_model', 'mProduct');
		$res = $this->mProduct->getSpecialProducts($data['single']['id']);
		if( $res ){
			foreach ( $res as $row ){
				$data['products'][$row['id']] = $row;
			}
		}
		//get today buyed
		$data['exists'] = array();
		if( $userInfo ){
			$day = date('Y-m-d');
			$condition = array(
				'AND' => array('uid='.$userInfo['uid'],"day='".$day."'"),
			);
			$res = $this->Base_model->getList('products_free',$condition);
			if( $res ){
				foreach ( $res as $row ){
					if( isset($data['products'][$row['product_id']]) ){
						$data['exists'][$row['product_id']] = $row['product_id'];
					}
				}
			}
		}
		//get current cart free product
		$cart = json_decode($this->input->cookie('cart',true),true);
		if( $cart ){
			foreach( $cart as $product_id=>$v ){
				if( isset($data['products'][$product_id]) ){
					$data['exists'][$product_id] = $product_id;
				}
			}
		}
		$this->load->view('special/'.$method, $data);
	}
	
	public function give(){
		$method = 'give';
		$data = array();
		$data['single'] = $this->Base_model->getSingle('specials','alias',$method);
		if( !$data['single'] ){
			show_404();
			return;
		}
		//check site_id
		$site_id = isset($_COOKIE['SITEID']) ? intval($_COOKIE['SITEID']) : 1;
		$sites = array();
		$condition = array(
			'AND' => array('special_id='.$data['single']['id']),
		);
		$res = $this->Base_model->getList('specials_site',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$sites[$row['site_id']] = $row;
			}
		}
		if( !isset($sites[$site_id]) ){
			show_404();
			return;
		}
		//get userInfo
		$userInfo = $this->session->userdata('userinfo');
		//get special products
		$data['products'] = array();
		$this->load->model('Product_model', 'mProduct');
		$res = $this->mProduct->getSpecialProducts($data['single']['id']);
		if( $res ){
			foreach ( $res as $row ){
				$data['products'][$row['id']] = $row;
			}
		}
		//get today buyed
		$data['paid'] = 0;
		$data['exists'] = array();
		//get current cart free product
		$cart = json_decode($this->input->cookie('cart',true),true);
		if( $cart ){
			foreach( $cart as $product_id=>$row ){
				//获取已经领取的免费商品
				if( isset($data['products'][$product_id]) && count($data['exists'])<=2 ){
					$data['exists'][$product_id] = $product_id;
				}
				//计算购物车中付费商品总价
				$p = $this->Base_model->getSingle('products','id',$product_id);
				if( !isset($data['exists'][$product_id]) ){
					$data['paid'] += $p['price']*$row['amount'];
				} else {
					if( $row['amount']>1 ){
						$data['paid'] += $p['price']*($row['amount']-1);
					}
				}
			}
		}
		$this->load->view('special/'.$method, $data);
	}

		public function give1(){
		$method = 'give1';
		$data = array();
		$data['single'] = $this->Base_model->getSingle('specials','alias',$method);
		if( !$data['single'] ){
			show_404();
			return;
		}
		//check site_id
		$site_id = isset($_COOKIE['SITEID']) ? intval($_COOKIE['SITEID']) : 1;
		$sites = array();
		$condition = array(
			'AND' => array('special_id='.$data['single']['id']),
		);
		$res = $this->Base_model->getList('specials_site',$condition);
		if( $res ){
			foreach ( $res as $row ){
				$sites[$row['site_id']] = $row;
			}
		}
		if( !isset($sites[$site_id]) ){
			show_404();
			return;
		}
		//get userInfo
		$userInfo = $this->session->userdata('userinfo');
		//get special products
		$data['products'] = array();
		$this->load->model('Product_model', 'mProduct');
		$res = $this->mProduct->getSpecialProducts($data['single']['id']);
		if( $res ){
			foreach ( $res as $row ){
				$data['products'][$row['id']] = $row;
			}
		}
		//get today buyed
		$data['paid'] = 0;
		$data['exists'] = array();
		//get current cart free product
		$cart = json_decode($this->input->cookie('cart',true),true);
		if( $cart ){
			foreach( $cart as $product_id=>$row ){
				//获取已经领取的免费商品
				if( isset($data['products'][$product_id]) && count($data['exists'])<=2 ){
					$data['exists'][$product_id] = $product_id;
				}
				//计算购物车中付费商品总价
				$p = $this->Base_model->getSingle('products','id',$product_id);
				if( !isset($data['exists'][$product_id]) ){
					$data['paid'] += $p['price']*$row['amount'];
				} else {
					if( $row['amount']>1 ){
						$data['paid'] += $p['price']*($row['amount']-1);
					}
				}
			}
		}
		$this->load->view('special/'.$method, $data);
	}


}