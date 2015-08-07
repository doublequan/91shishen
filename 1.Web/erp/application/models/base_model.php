<?php

class Base_model extends CI_Model {
	
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * @param string $table
     * @param string $key
     * @param string $val
     * @param string $column
     * @return array|boolean
     */
    public function getSingle( $table, $key, $val, $column='*' ){
    	$sql = "SELECT {$column} FROM {$table} WHERE `{$key}`='{$val}'";
    	$q = $this->db->query($sql);
    	return $q ? $q->first_row('array') : false;
    }

    /**
     * 通用获取列表
     * @param string $table
     * @param string $condition
     * @param string $column
     * @param string $orderby
     * @param integer $page
     * @param integer $size
     * @return array
     */
    public function getList( $table, $condition=array(), $column='*', $orderby='', $page=0, $size=0 ){
    	$sql = "SELECT {$column} FROM {$table} WHERE 1";
    	if( isset($condition['AND']) && !empty($condition['AND']) ){
    		foreach( $condition['AND'] as $v ){
    			$sql .= ' AND '.$v;
    		}
    	}
    	if( isset($condition['OR']) && !empty($condition['OR']) ){
    		foreach( $condition['OR'] as $arr ){
    			$sql .= ' AND ('.implode(' OR ', $arr).')';
    		}
    	}
    	if( $orderby ){
    		$sql .= ' ORDER BY '.$orderby;
    	}
    	if( $page ){
    		return $this->pagerQuery($sql,$page,$size);
    	} else {
    		if( $size ){
    			$sql .= ' LIMIT '.$size;
    		}
    		return $this->getAll($sql);
    	}
    }
    
    /**
     * 通用获取列表
     * @param string $table
     * @param string $condition
     * @param string $column
     * @param string $orderby
     * @param integer $page
     * @param integer $size
     * @return array
     */
    public function countList( $table, $condition=array()){
    	$sql = "SELECT count(*) FROM {$table} WHERE 1";
    	if( isset($condition['AND']) && !empty($condition['AND']) ){
    		foreach( $condition['AND'] as $v ){
    			$sql .= ' AND '.$v;
    		}
    	}
    	if( isset($condition['OR']) && !empty($condition['OR']) ){
    		foreach( $condition['OR'] as $arr ){
    			$sql .= ' AND ('.implode(' OR', $arr).')';
    		}
    	}
    	return $this->getAll($sql);
    }
    
    /**
     * 通用获取列表
     * @param string $table
     * @param string $condition
     * @param string $column
     * @param string $orderby
     * @param integer $page
     * @param integer $size
     * @return array
     */
    public function getCount( $table, $condition=array()){
        $sql = "SELECT count(*) AS num FROM {$table} WHERE 1";
        if( isset($condition['AND']) && !empty($condition['AND']) ){
            foreach( $condition['AND'] as $v ){
                $sql .= ' AND '.$v;
            }
        }
        if( isset($condition['OR']) && !empty($condition['OR']) ){
            foreach( $condition['OR'] as $arr ){
                $sql .= ' AND ('.implode(' OR', $arr).')';
            }
        }
        return $this->getOne($sql);
    }

    /**
     * 通用插入数据
     * @param string $table
     * @param array $params
     * @return number|boolean
     */
    public function insert( $table, $params ){
    	if( $this->db->query(dbInsert($table, $params)) ){
    		$params['id'] = $this->insert_id();
    		return $params;
    	}
    	return false;
    }
    
    /**
     * 通用插入多行数据
     * @param string $table
     * @param array $params
     */
    public function insertMulti( $table, $params ){
    	$this->db->query(dbInsertBatch($table, $params));
    }
    
    /**
     * 通用插入或更新唯一主键数据
     * @param string $table
     * @param array $insertData
     * @param array $updateData
     */
    public function insertDuplicate( $table, $insertData, $updateData ){
    	$sql = dbInsert($table, $insertData);
    	$sql .= ' ON DUPLICATE KEY UPDATE ';
    	$update = array();
    	foreach ( $updateData as $k=>$v ){
    		$update[] = "`{$k}`='{$v}'";
    	}
    	$sql .= implode(',', $update);
    	$this->db->query($sql);
    }
    
    /**
     * 通用更新功能，whereMap仅支持等于条件
     * @param string $table
     * @param array $params
     * @param array $whereMap example: array('id'=>1,'status'=>2)
     * @return integer 受影响的行数
     */
    public function update( $table, $params, $whereMap ){
    	if( $whereMap ){
            $condition = array();
            foreach( $whereMap as $k=>$v ){
                $condition[] = "`".$k."`='".$v."'";
            }
            $where = implode(' AND ',$condition);
    	    $this->db->query( dbUpdate($table, $params, $where) );
            return $this->db->affected_rows();
        }
        return 0;
    }
    
    /**
     * 通用更新功能，whereMap仅支持等于条件
     * @param string $table
     * @param string $column
     * @param integer step
     * @param array $whereMap example: array('id'=>1,'status'=>2)
     * @return integer 受影响的行数
     */
    public function updateNumber( $table, $column, $step, $whereMap ){
    	if( $table && $column && $step && $whereMap ){
    		$sql = 'UPDATE '.$table.' SET `'.$column.'`=`'.$column.'`+'.$step;
    		$condition = array();
    		foreach( $whereMap as $k=>$v ){
    			$condition[] = "`".$k."`='".$v."'";
    		}
    		$where = implode(' ',$condition);
    		$sql .= ' WHERE '.$where;
    		$this->db->query($sql);
    		return $this->db->affected_rows();
    	}
    	return 0;
    }
    
    /**
     * 删除，real为是否物理删除，whereMap仅支持等于条件
     * @param string $table
     * @param integer $whereMap example: array('id'=>1,'status'=>2)
     * @param boolean $real
     * @return integer 受影响的行数
     */
    public function delete( $table, $whereMap, $real=false ){
        if( $whereMap ){
            $condition = array();
            foreach( $whereMap as $k=>$v ){
                $condition[] = "`".$k."`='".$v."'";
            }
            $where = implode(' AND ',$condition);
    	    if( $real ){
                $sql = 'DELETE FROM '.$table.' WHERE '.$where;
            } else {
                $params = array('is_del'=>1);
                $sql = dbUpdate($table, $params, $where);
            }
            $this->db->query($sql);
            return $this->db->affected_rows();
        }
        return 0;
    }
    
    /**
     * 获取单个查询结果
     * @param string $sql
     */
    public function getOne($sql){
    	$q = $this->db->query($sql);
    	$res = $q ? $q->first_row('array') : false;
    	return $res ? current($res) : $res;
    }
    
    /**
     * 获取单行查询结果
     * @param string $sql
     * @return array
     */
    public function getRow($sql){
    	$q = $this->db->query($sql);
    	return $q ? $q->first_row('array') : false;
    }

    /**
     * 获取多行查询结果
     * @param string $sql
     * @return array
     */
    public function getAll($sql){
    	$q = $this->db->query($sql);
    	return $q ? $q->result_array( 'array' ) : false;
    }
    
    /**
     * 获取最后插入的自增ID
     * @return integer
     */
    public function insert_id(){
    	 return $this->db->insert_id();
    }
        
    /**
     * @access	public
     * ------------------------------------------------
     * @param	$sql			SQL语句
     * @param	$page			当前页面，为0则返回全部，不包含分页
     * @param	$size			页面数量
     * ------------------------------------------------
     * @desc	通用获取多行结果集和分页
     * ------------------------------------------------
     * @return	$ret->results	结果集
     * 			$ret->pager		分页
     */
    public function pagerQuery( $sql, $page=0, $size=10 ){
    	$result = new stdClass();
    	$result->results = false;
    	$result->pager = false;
        if( $page==0 ){
            if( $size ){
            	$sql .= ' LIMIT 0,'.$size;
            }
            $res = $this->getAll($sql);
            if( $res ){
                $result->results = $res;
            }
        } else {
	        $set = ($page-1)*$size;
	        $sql = preg_replace('/select|SELECT/', 'SELECT SQL_CALC_FOUND_ROWS',$sql,1);
	        $sql .= ' LIMIT '.$set.','.$size;
	        $res = $this->getAll($sql);
	        if( $res ){
		        $total = intval( $this->getOne('SELECT FOUND_ROWS() as numrows') );
		        if( $total>0 ){
		            $pages = $total%$size ? intval($total/$size)+1 : intval($total/$size);
		            $pager['total'] = $total;
		            $pager['pages'] = $pages;
		            $pager['page'] = $page;
		            $pager['size'] = $size;
		            $pager['next'] = $page==$pages ? 0 : $page+1;
		            $pager['prev'] = $page==1 ? 0 : $page-1;
		            $pager['pagesize'] = count($res);
		            if( $pages <= 7 ){
		                for($i=1;$i<=$pages;$i++){
		                    $pager['pageArray'][] = $i;
		                }
		            } else {
		                for( $i=$page-3; $i<=$page+3; $i++ ){
		                    if( $page<=3 ){
		                        $j = $i+4-$page;
		                    } elseif ( $page>3 && $page<$pages-3 ){
		                        $j = $i;
		                    } else {
		                        $j = $i+$pages-$page-3;
		                    }
		                    $pager['pageArray'][] = $j;
		                }
		            }
		            $result->results = $res;
		            $result->pager  = $pager;
		        }
	        }
        }
        return $result;
    }
    
    /**
     * 封装分页函数
     * @return array
     */
    public function pageArray( $total, $page=1, $size=10 ){
    	$pager = false;
    	if( $total>0 ){
    		$pages = $total%$size ? intval($total/$size)+1 : intval($total/$size);
    		$pager['total'] = $total;
    		$pager['pages'] = $pages;
    		$pager['page'] = $page;
    		$pager['size'] = $size;
    		$pager['next'] = $page==$pages ? 0 : $page+1;
    		$pager['prev'] = $page==1 ? 0 : $page-1;
    		$pager['pagesize'] = $page==$pages ? $total-($page-1)*$size : 10;
    		if( $pages <= 7 ){
    			for($i=1;$i<=$pages;$i++){
    				$pager['pageArray'][] = $i;
    			}
    		} else {
    			for( $i=$page-3; $i<=$page+3; $i++ ){
    				if( $page<=3 ){
    					$j = $i+4-$page;
    				} elseif ( $page>3 && $page<$pages-3 ){
    					$j = $i;
    				} else {
    					$j = $i+$pages-$page-3;
    				}
    				$pager['pageArray'][] = $j;
    			}
    		}
    	}
    	return $pager;
    }
}