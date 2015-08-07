<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('create_captcha'))
{
	function imageUpload($tmp_img_path){
		$file_path = FALSE;
		if(!file_exists($tmp_img_path))
			return $file_path;

		$relative_folder = '/upload/image/'.date('Y-m', time()).'/';
		$upload_folder = ROOTPATH.$relative_folder;
		if(!is_dir($upload_folder))
			mkdir($upload_folder, 0777, true);

		$file_type = strrchr($tmp_img_path, '.');
		$file_path = $relative_folder.time().rand(100000, 999999).$file_type;
		move_uploaded_file($tmp_img_path, ROOTPATH.$file_path);

		return $file_path;
	}
}

if ( ! function_exists('getTreeFromArray'))
{
	function getTreeFromArray($data, $pid = 0, $key = 'id', $pKey = 'father_id', $childKey = 'child', $maxDepth = 0){  
	    static $depth = 0;  
	    $depth++;  
	    if (intval($maxDepth) <= 0)  
	    {  
	        $maxDepth = count($data) * count($data);
	    }
	    if($maxDepth == 1){
	    	return $data;
	    }
	    if ($depth > $maxDepth)  
	    {  
	        exit("error recursion:max recursion depth {$maxDepth}");  
	    }  
	    $tree = array();  
	    foreach ($data as $rk => $rv)  
	    {  
	        if ($rv[$pKey] == $pid)  
	        {
	            $rv[$childKey] = getTreeFromArray($data, $rv[$key], $key, $pKey, $childKey, $maxDepth);  
	            $tree[] = $rv;  
	        }  
	    }  
	    return $tree;  
	} 
}
if ( ! function_exists('getTreeOptions'))
{
	function getTreeOptions($categorys, $depth=0){
	    $indent_str = $options = '';
	    if($depth > 0){
	        //$indent_str = '&lfloor;'.str_repeat('&minus;', $depth * 2 - 1);
	        $indent_str = str_repeat('&nbsp;&nbsp;&nbsp;', $depth * 2);
	    }
	    $depth++;
	    foreach ($categorys as $category) {
	        if(is_array($category['child']) && count($category['child']) > 0){
	        	$options .= '<option class="cate_ops" value="'.$category['id'].'" style="background-color:#dedede;">'.$indent_str.$category['name'].'</option>';
	            $options .= getTreeOptions($category['child'], $depth);
	        } else {
	        	$options .= '<option class="cate_ops" value="'.$category['id'].'">'.$indent_str.$category['name'].'</option>';
	        }
	    }
	    return $options;
	}
}
if ( ! function_exists('getDeptTreeOptions'))
{
	function getDeptTreeOptions($depts, $depth=0){
	    $indent_str = $options = '';
	    if($depth > 0){
	        $indent_str = str_repeat('&nbsp;', $depth * 2);
	    }
	    $depth++;
	    foreach ($depts as $dept) {
	        $options .= '<option class="dept_ops" value="'.$dept['id'].'" company_id="'.$dept['company_id'].'">'.$indent_str.$dept['name'].'</option>';
	        if(is_array($dept['child']) && count($dept['child']) > 0){
	            $options .= getDeptTreeOptions($dept['child'], $depth);
	        }
	    }
	    return $options;
	}
}
