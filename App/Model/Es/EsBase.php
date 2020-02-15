<?php 
namespace App\Model\Es;

use \EasySwoole\Core\Component\Di;

/**
 *  es 只关心搜索方法
 */
class EsBase
{
	/**
	 *	es 对象
	 */
	public $esClient = null;

	public function __construct(){
		$this->esClient = Di::getInstance()->get('ES');
	}

	
	/** 
	 *	通过 name 查询
	 */
	public function searchByName($name,$from = 0, $size = 2, $type = 'match')
	{
		if(empty(trim($name))){
			return [];
		}
		// 测试es
		$params = [
			"index"=>$this->index,
			//"id"=>1,
			"body"=>[
				'query'=>[
					$type=>[
						'name' => $name
					],
				],
				'from' => $from,
				'size' => $size
			],
		];

		//var_dump($client);
		return $this->esClient->search($params);
	}


}