<?php 
namespace App\HttpController\Api;

use EasySwoole\Core\Utility\Validate\Rules;
use EasySwoole\Core\Utility\Validate\Rule;
use EasySwoole\Core\Http\Message\Status;
use EasySwoole\Core\Component\Logger;
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
	 *	视频数据插入接口
	 *	http://150.109.46.180:9501/api/video/add
	 */
	public function add()
	{
		$params = $this->request()->getRequestParam();

		// 写入日志
		Logger::getInstance()->log($this->logType.json_encode($params));
		// 数据校验
		$ruleObj = new Rules();
		$ruleObj->add('name','视频名称错误')->withRule(Rule::REQUIRED)->withRule(Rule::MIN_LEN,2);
		$ruleObj->add('url','视频地址错误')->withRule(Rule::REQUIRED);
		$ruleObj->add('image','图片地址错误')->withRule(Rule::REQUIRED);
		$ruleObj->add('content','视频内容错误')->withRule(Rule::REQUIRED);
		$validate = $this->validateParams($ruleObj);
		if($validate->hasError()){
			return $this->writeJson(Status::CODE_BAD_REQUEST,['error'=>$validate->getErrorList()->first()->getMessage()],'提交数据有误');
		}
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
