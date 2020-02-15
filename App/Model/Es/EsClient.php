<?php 
namespace App\Model\Es;

use EasySwoole\Component\Singleton;
use Elasticsearch\ClientBuilder;

/**
 *  es 基础类库
 */
class EsClient 
{
	// trait 复用
	use Singleton;

	// es对象
	public $esClient = null; 

	public function __construct()
	{
		$config = \Yaconf::get('es');
		// 实例化es对象
		try {
			$this->esClient = ClientBuilder::create()->setHosts([$config['host'].':'.$config['port']])->build();
		} catch (\Exception $e) {
			throw new \Exception("es 连接失败");
		}

		if(empty($this->esClient)){
			throw new \Exception("es 内部错误");
		}
	}

	/** 
	 *	魔术方法
	 */	
	public function __call($name,$arg)
	{
		return $this->esClient->$name(...$arg);
	}
}