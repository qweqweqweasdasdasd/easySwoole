<?php 
namespace App\Lib\Redis;

use EasySwoole\Core\AbstractInterface\Singleton;
use \EasySwoole\Config;

/**
 * Redis 基础类库
 */
class Redis
{
	// trait 复用
	use Singleton;	
	public $redis = '';

	public function __construct()
	{
		// 判断 redis.so 文件是否存在
		if(!extension_loaded('redis')){
			throw new \Exception("Redis.so is not exist ");
		}
		// 连接redis 
		try {
			$this->redis = new \Redis();
			// $redisConfig = Config::getInstance()->getConf('REDIS');
			$redisConfig = \Yaconf::get('redis');
			$result = $this->redis->connect($redisConfig['host'],$redisConfig['port'],$redisConfig['time_out']);
		} catch (\Exception $e) {
			throw new \Exception("redis server error");
		}
		// 判断是否连接失败
		if($result === false){
			throw new \Exception("redis connect is error");
		}
	}

	/**
	 *	set数据到redis
	 */
	public function set($key,$value,$time=0)
	{
		if(empty($key)){
			return '';
		}
		if(is_array($value)){
			json_encode($value);
		}
		if(empty($time)){
			return $this->redis->set($key,$value);
		}
		return $this->redis->setx($key,$time,$value);
	}

	/**
	 *	获取到制定key的value
	 */
	public function get($key)
	{
		if(empty($key)){
			return 'empty';
		}

		return $this->redis->get($key);
	}

	/**
	 *	出队列lpop
	 */
	public function lPop($key)
	{
		if(empty($key)){
			return 'empty';
		}

		return $this->redis->lpop($key);
	}

	/**
	 *	入队列rPush
	 */
	public function rPush($key,$value)
	{
		if(empty($key) || empty($value)){
			return 'empty';
		}

		return $this->redis->rpush($key,$value);
	}
}