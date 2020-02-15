<?php 
namespace App\HttpController\Api;

use EasySwoole\EasySwoole\Task\TaskManager;
// use EasySwoole\Utility\Validate\Rules;
// use EasySwoole\Utility\Validate\Rule;
use EasySwoole\Http\Message\Status;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Component\Di;
use App\Model\Video as VideoModel;
use App\HttpController\Api\Base;

/**
 * 视频接口
 */
class Video extends Base
{
	/**
	 *	视频日志类型
	 */
	public $logType = 'video:';

	/**
	 *	视频基本信息获取
	 *	http://150.109.46.180:9501/api/video/index
	 */
	public function index()
	{
		$id = (int)$this->request()->getRequestParam()['id'];
		if(empty($id)){
			return $this->writeJson(Status::CODE_BAD_REQUEST,[],'请求不合法');
		}

		// 获取视频基本信息
		try {
			$video = (new VideoModel())->getById($id);
		} catch (\Exception $e) {
			// 记录日志
			return $this->writeJson(Status::CODE_BAD_REQUEST,[],'服务器内部错误'); 
		}

		if(!$video || $video['status'] != \Yaconf::get('video_status.normal')){
			return $this->writeJson(Status::CODE_BAD_REQUEST,[],'该视频不存在！');
		}

		// 视频数据格式化
		$video['create_time'] = date('Y-m-d H:i:s',$video['create_time']);
		$video['duration'] = gmstrftime("%H:%M:%S",$video['duration']);
		$video['score'] = Di::getInstance()->get('Redis')->zscore(\Yaconf::get('redis.video_play_num_key'),$id);
		// task异步处理业务
		TaskManager::getInstance()->async(function() use($id){
			var_dump('task-id-'.$id);

			// 按天排行记录数据
			// 总排行记录数据
			$res = Di::getInstance()->get('Redis')->zincrby(\Yaconf::get('redis.video_play_num_key'),1,$id);
			// 是否保存成功保存到日志内
		});

		return $this->writeJson(Status::CODE_OK,['id'=>$video],'success');
	}


	/** 
	 *	视频点播次数的总排行
	 *	http://150.109.46.180:9501/api/video/rank
	 */
	public function rank()
	{
		$rank = Di::getInstance()
				->get('Redis')
				->zrevrange(\Yaconf::get('redis.video_play_num_key'),0,10,true);
		// 完善业务流程
		return $this->writeJson(Status::CODE_OK,['data'=>$rank],'success');
	}

	/**
	 *	视频数据插入接口
	 *	http://150.109.46.180:9501/api/video/add
	 */
	public function add()
	{
		$params = $this->request()->getRequestParam();

		// 写入日志
		Logger::getInstance()->log($this->logType.json_encode($params));
		// 数据校验
		// $ruleObj = new Rules();
		// $ruleObj->add('name','视频名称错误')->withRule(Rule::REQUIRED)->withRule(Rule::MIN_LEN,2);
		// $ruleObj->add('url','视频地址错误')->withRule(Rule::REQUIRED);
		// $ruleObj->add('image','图片地址错误')->withRule(Rule::REQUIRED);
		// $ruleObj->add('content','视频内容错误')->withRule(Rule::REQUIRED);
		// $validate = $this->validateParams($ruleObj);
		// if($validate->hasError()){
		// 	return $this->writeJson(Status::CODE_BAD_REQUEST,['error'=>$validate->getErrorList()->first()->getMessage()],'提交数据有误');
		// }
		$data = [
			'name' => $params['name'],
			'url' => $params['url'],
			'image' => $params['image'],
			'content' => $params['content'],
			'cate_id' => $params['cate_id'],
			'create_time' => time(),
			'uploader' => 'puber',
			// 上传者
			'status' => \Yaconf::get("status.normal"),	// 0 未审核 1 审核 2 审核失败
		];

		// 插入
		try {
			$modelObj = new VideoModel();
			$videoId = $modelObj->add($data);
		} catch (\Exception $e) {
			return $this->writeJson(Status::CODE_BAD_REQUEST,[],'服务器内部错误');
		}

		if(!empty($videoId)){
			return $this->writeJson(Status::CODE_OK,['id'=>$videoId],'success');
		}else{
			return $this->writeJson(Status::CODE_BAD_REQUEST,['id'=>0],'提交数据有误');
		}

	}
}
