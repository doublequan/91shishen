<?php
/**
 * 会员信息
 * @author 
 * @date 2014-8-11
 */
class Employee_model extends CI_Model{

	function __construct(){

		parent::__construct();

	}
	
	/**
	 * 检查用户邀请码
	 * @param string $username
	 * @return array
	 */
	public function selectInviteCode($username = ''){

		$this->db->select('invite_code','username');
		$this->db->where('username',$username);
		$this->db->or_where('mobile', $username);
		$query = $this->db->get('employees');

		if($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return false;
		}
		
	}
	
}