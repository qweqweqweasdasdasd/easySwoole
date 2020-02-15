<?php 
namespace App\HttpController\Api;

use EasySwoole\Core\Http\Message\Status;
use \EasySwoole\Core\Component\Di;
use App\HttpController\Api\Base;
use App\Lib\AliyunSdk\AliVod;
use App\Lib\Redis\Redis;
use App\Model\Video as VideoModel;
use App\Lib\Cache\Video as videoCache;
use App\Model\Es\EsVideo;

/**
 * api 接口
 */
class Index extends Base
{
	/**
	 *	点播视频列表接口	第二套方案：10分钟定时生成数据之后从json文件内读取数据
	 *	http://150.109.46.180:9501/api/index/lists
	 */
	public function getVideoCahceFileJsonData()
	{
		$cateId = !empty($this->params['cate_id']) ? intval($this->params['cate_id']):0;

		// file
		//$videoFile = EASYSWOOLE_ROOT . '/' .'webroot/json/video' . '/' . $cateId . '.json';
		//$videoData = is_file($videoFile) ? file_get_contents($videoFile) :[];

		// 使用redis获取到数据
		// $videoData = Di::getInstance()->get('Redis')->get('index_video_data_cate_id_'.$cateId);
		// $videoData = !empty($videoData) ? json_decode($videoData,true):[];
			
		try {
			$videoData = (new videoCache())->getVideoCache($cateId);
		} catch (\Exception $e) {
			return $this->writeJson(Status::CODE_BAD_REQUEST,[],'fail');
		}

		$count = count($videoData);
		return $this->writeJson(Status::CODE_OK,$this->getPagingDatas($count,$videoData),'success');
	}

	/**
	 *	点播视频列表接口	第一套方案：直接到数据库内取出数据
	 *	http://150.109.46.180:9501/api/index/lists
	 */
	public function getVideoData()
	{
		// $params = $this->request()->getRequestParam();
		// $page = !empty($params['page'])?intval($params['page']):1;
		// $size = !empty($params['size'])?intval($params['size']):10;
		$condition = [];
		if(!empty($this->params['cate_id'])){
			$condition['cate_id'] = intval($this->params['cate_id']);
		}

		try {
			$videoModel = new VideoModel();
			$data = $videoModel->getVideoData($condition,$this->params['page'],$this->params['size']);
		} catch (\Exception $e) {
			return $this->writeJson(Status::CODE_BAD_REQUEST,[],'fail');
		}

		// 组装数据 
		if(!empty($data['lists'])){
			foreach ($data['lists'] as $k => &$v) {
				$v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
				$v['duration'] = gmstrftime("%H:%M:%S",$v['duration']);
			}
		}

		return $this->writeJson(Status::CODE_OK,$data,'success');
	}

	/**
	 *	测试定时缓存接口
	 *	http://150.109.46.180:9501/api/index/testVideoCache
	 */
	public function testVideoCache()
	{
		$videoCacheObj = new videoCache();
		$videoCacheObj->setIndexVideo();
	}

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

	/**
	 *	测试阿里云vod
	 *	http://150.109.46.180:9501/api/index/testAliVod
	 */
	public function testAliVod()
	{
		$obj = new Alivod();
        $title = "test-".mt_rand(10000,99999);
        $videoName = '1.mp4';

        $result = $obj->createUploadVideo($title,$videoName);

        $UploadAddress = json_decode(base64_decode($result->UploadAddress),true);
        $UploadAuth = json_decode(base64_decode($result->UploadAuth),true);

        // 初始化 ossclient
        $obj->initOssClient($UploadAuth,$UploadAddress);
        // 上传文件
        $videoFile = '/home/wwwroot/es/webroot/video/2019/10/20191023165512298564.mp4';  // 使用临时文件即可上传到 aliyun 点播
        $result = $obj->uploadLocalFile($UploadAddress,$videoFile);
        var_dump($result);
	}

	/**
	 *	测试阿里云vod 
	 */
	public function getVideoInfo()
	{
		$videoID = 'bf7c45cd2aaf43f5af4e33e460a63cc7';

        $obj = new Alivod();

        var_dump($obj->getPlayerInfo($videoID));
	}

	/**
	 *	测试es-demo
	 *	http://150.109.46.180:9501/api/index/demo
	 */
	public function demo()
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
		$client = Di::getInstance()->get('ES');
		//var_dump($client);
		$res = $client->search($params);

		return $this->writeJson(200,$res,'success');
	}

	/**
	 *	通过es自己封装的类进行查询
	 */
	public function demo2()
	{
		$res = (new EsVideo())->searchByName($this->params['name']);
		
		return $this->writeJson(200,$res,'success');
	}

}
