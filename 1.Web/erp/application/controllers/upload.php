<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class upload extends CI_Controller 
{
	public function __construct(){
		parent::__construct();
	}

	public function editorUpload(){
		$file = uploadFile('upfile');
		$fileType = strtolower(strrchr($_FILES['upfile']['name'],'.'));
		echo '{"state":"SUCCESS","url":"'.$file.'","fileType":"'.$fileType.'","original":"'.$_FILES['upfile']["name"].'"}';;
	}
	
	public function multiUpload(){
		$img = uploadFile('Filedata');
		$thumb = createThumb($img,300,300);
		$ret['img'] = $img;
		$ret['thumb'] = $thumb;
		echo json_encode($ret);
	}
	
	public function productImageUpload(){
		$img = uploadFile('Filedata');
		$thumb = createThumb($img,300,300);
		$ret['img'] = $img;
		$ret['thumb'] = $thumb;
		echo json_encode($ret);
	}
	
	public function kindeditor(){
		$ret = array();
		$res = uploadFile('imgFile');
		if( isset($res['url']) && $res['url'] ){
			$ret = array(
				'error'	=> 0,
				'url'	=> $res['url']
			);	
		} else {
			$ret = array(
				'error'	=> 1,
				'url'	=> '文件上传失败'
			);
		}
		echo json_encode($ret);
		exit;
	}
	
	/**
	 * upload and handle image
	 */
	public function img(){
		$ret = array('img'=>'','thumb'=>'','msg'=>'');
		do{
			//get parameters
			$must = array();
			$fields = array('is_thumb','thumb_width','thumb_height','is_resize','resize_width','resize_height');
			$params = array();
			foreach ( $fields as $v ){
				$params[$v] = intval($this->input->post($v));
			}
			unset($v);
			//check request
			/**
			if( !self::$user ){
				$ret['msg'] = 'user is not login';
				break;
			}
			*/
			$img = uploadFile('img');
			if( !($img && file_exists($img['file'])) ){
				$ret['msg'] = 'image upload failure';
				break;
			}
			$imgInfo = getImageInfo($img['file']);
			if( !($imgInfo && isset($imgInfo['type']) && in_array($imgInfo['type'], array('jpeg','png','gif','bmp'))) ){
				$ret['msg'] = 'image type is not allowed';
				break;
			}
			$ret = array(
				'msg'	=> 'success',
				'img'	=> $img['url'],
				'thumb'	=> '',
			);
			//create thumb			
			if( $params['is_thumb']==1 ){
				$config = array();
				$config['image_library'] = 'gd2';
				$config['source_image'] = $img['file'];
				$config['quality'] = 100;
				$config['create_thumb'] = TRUE;
				$config['width'] = $params['thumb_width'];
				$config['height'] = $params['thumb_height'];
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();
				$this->image_lib->clear();
				$ext = strrchr($img['url'], '.');
				$ret['thumb']	= str_replace($ext, '_thumb'.$ext, $img['url']);
			}
			//resize
			if( $params['is_resize']==1 ){
				$config = array();
				$config['image_library'] = 'gd2';
				$config['source_image'] = $img['file'];
				$config['quality'] = 100;
				$config['width'] = $params['resize_width'];
				$config['height'] = $params['resize_height'];
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();
				$this->image_lib->clear();
			}
		} while (false);
		echo json_encode($ret);
		exit;	}
}
