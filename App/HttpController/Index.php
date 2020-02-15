<?php 
namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;
use Elasticsearch\ClientBuilder;

/**
 * 测试接口控制
 */
class Index extends Controller
{
	public function index()
	{
		return $this->response()->write('swoole');
	}

	/**
	 *	测试PHP es domo
	 *	http://150.109.46.180:9501/index/testEs
	 */
	public function testEs()
	{
		// 测试es
		$params = [
			"index"=>"es_video",
			//"id"=>1,
			"body"=>[
				'query'=>[
					'match'=>[
						'name' => '刘德华'
					],
				],
			],
		];

		// 实例化es对象
		$client = ClientBuilder::create()->setHosts(["127.0.0.1:9200"])->build();
		$res = $client->search($params);

		return $this->writeJson(200,$res,'success');
	}
}
