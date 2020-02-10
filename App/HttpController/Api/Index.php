<?php 
namespace App\HttpController\Api;

use \EasySwoole\Core\Component\Di;
use App\HttpController\Api\Base;
use App\Lib\Redis\Redis;

/**
 * api 接口
 */
class Index extends Base
{
	/**
	 *	http://150.109.46.180:9501/api/index/index
	 */
	/*public function index()
	{
		return $this->response()->write('api action');
	}*/

	/**
	 *	http://150.109.46.180:9501/api/index/video?mt=123
	 */
	public function video()
	{
		//new abc();
		$data = [
			'id' => 1,
			'name' => 'ulimit -n',
			'params' => $this->request()->getRequestParam()
		];

		return $this->writeJson(200,$data,'success');
	}

	/**
	 *	数据库测试
	 *	http://150.109.46.180:9501/api/index/getVideo
	 */
	public function getVideo()
	{
		$db = Di::getInstance()->get('MYSQL');
		$result = $db->where('id',1)->getOne('video');
		//$result = \Db::table('video')->select();

		return $this->writeJson(200,$result,'success');
	}

	/**
	 *	测试redis
	 *	http://150.109.46.180:9501/api/index/getRedis
	 */
	public function getRedis()
	{
		// $redis = new \Redis();
		// $redis->connect('127.0.0.1',6379,5);
		// $redis->set("swoole","123456");

		$redis = Redis::getInstance();	// 使用单例模式
		$redis = new Redis();			// 每次new都是一个进程
		$redis = Di::getInstance()->get('Redis');	//Di 注入对象

		$result = $redis->get('swoole');
		var_dump($redis);
		return $this->writeJson(200,$result,'success');
	}

	/**
	 *	写入队列
	 *	http://150.109.46.180:9501/api/index/pub
	 */
	public function pub()
	{
		$params = $this->request()->getRequestParam();

		$phone = $params['phone'];
		$result = Di::getInstance()->get('Redis')->rPush('swoole_list_test',$phone);

		return $this->writeJson(200,$result,'success');
	}
}
