<?php 
namespace App\HttpController\Api;

use \EasySwoole\Core\Component\Di;
use App\HttpController\Api\Base;

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
		//$db = Di::getInstance()->get('MYSQL');
		//$result = $db->where('id',1)->getOne('video');
		$result = \Db::table('video')->select();

		return $this->writeJson(200,$result,'success');
	}
}
