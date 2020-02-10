<?php 
namespace App\Model;

use \EasySwoole\Core\Component\Di;
/**
 * 
 */
class Base
{	
	public $db = '';

	public function __construct()
	{
		if(empty($this->tableName)){
			throw new \Exception("数据表不得为空！");
		}

		$db = Di::getInstance()->get('MYSQL');
		if($db instanceof \MysqliDb){
			$this->db = $db;
		}else{
			throw new \Exception("db fali");
		}
	}

	/**
	 *	写入数据库
	 */
	public function add($data)
	{
		if(empty($data) || !is_array($data)){
			return false;
		}

		return $this->db->insert($this->tableName,$data);
	}
}