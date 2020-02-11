<?php 
namespace App\Lib\Cache;

use \EasySwoole\Core\Component\Di;
use App\Model\Video as videoModel;
use App\Lib\Util\Tools;
/**
 * 视频首页缓存
 */
class Video 
{	
	/**
	 *	文件保存路径
	 */
	public $fileCachePath = 'webroot/json/video';


	public function setIndexVideo()
	{
		$cateIds = array_keys(\Yaconf::get("category.cates"));
		array_unshift($cateIds, 0);
		$cacheType = \Yaconf::get('base.indexCacheFuc');

		// 根据栏目获取到数据
		$videoModelObj = new videoModel();
		foreach ($cateIds as $k => $cateId) {
			$condition=[];
			if(empty($condition)){
				$condition['cate_id'] = $cateId;
			}

			// 获取到缓存数据如果失败了为空
			try {
				$data = $videoModelObj->getVideoCacheData($condition,1,1000);
			} catch (\Exception $e) {
				$data = [];	
			}

			// 如果为空数据的话跳出
			if(empty($data)){
				// 短信报警
				continue;
			}

			// 数据格式化
			foreach ($data as $k => &$v) {
				$v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
				$v['duration'] = gmstrftime("%H:%M:%S",$v['duration']);
			}

			switch ($cacheType) {
				case 'file':
					// 写入文件 FIEL
					Tools::mk_dir($this->fileCachePath);
					$flag = file_put_contents($this->getVideoCacheIndex($cateId), json_encode($data));
					break;
				case 'redis':
					// 写入redis
					$flag = Di::getInstance()->get('Redis')->set($this->getVideoCacheRedisIndex($cateId),json_encode($data));
					break;
				default:
					throw new \Exception("base.ini 缓存类型没有配置");
					break;
			}

			if(empty($flag)){
				// 短信报警 || 记录日志
				echo 'cate_id '.$cateId.' put data error'.PHP_EOL;
			}else{
				echo 'cate_id '.$cateId.' put data success'.PHP_EOL;
			}
		}	
	}

	/** 
	 *	获取到缓存数据
	 */
	public function getVideoCache($cateId = 0)
	{
		$cacheType = \Yaconf::get('base.indexCacheFuc');
		switch ($cacheType) {
			case 'file':
				// file
				$videoFile = $this->getVideoCacheIndex($cateId);
				$videoData = is_file($videoFile) ? file_get_contents($videoFile) :[];
				$videoData = !empty($videoData) ? json_decode($videoData,true):[];
				break;
			
			case 'redis':
				// 使用redis获取到数据
				$videoData = Di::getInstance()->get('Redis')->get($this->getVideoCacheRedisIndex($cateId));
				$videoData = !empty($videoData) ? json_decode($videoData,true):[];
				break;

			default:
				throw new \Exception("base.ini 缓存类型没有配置");
				break;
		}

		return $videoData;
	}

	/**
	 *	文件保存路径
	 */
	public function getVideoCacheIndex($cateId = 0)
	{
		return EASYSWOOLE_ROOT . '/' . $this->fileCachePath . '/' . $cateId . '.json';
	}

	/**
	 *	redis keys
	 */
	public function getVideoCacheRedisIndex($cateId = 0)
	{
		return 'index_video_data_cate_id_'.$cateId;
	}
}