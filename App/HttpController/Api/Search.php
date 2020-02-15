<?php 
namespace App\HttpController\Api;

use EasySwoole\Http\Message\Status;
use App\Model\Es\EsVideo;

/**
 * 搜索接口
 */
class Search extends Base
{

	/**
	 *	站内搜索接口
	 *	http://150.109.46.180:9501/api/search/index
	 */
	public function index()
	{
		$keyword = trim($this->params['keyword']);
		var_dump($keyword);
		if(empty($keyword)){
			return $this->writeJson(Status::CODE_OK,$this->getPagingDatas(0,[],0),'success');
		}

		$esObj = new EsVideo();
		$res = $esObj->searchByName($keyword,$this->params['from'],$this->params['size']);

		if(empty($res)){
			return $this->writeJson(Status::CODE_OK,$this->getPagingDatas(0,[],0),'success');
		}

		$hits = $res['hits']['hits'];
		$total = $res['hits']['total']['value'];

		if(empty($total)){
			return $this->writeJson(Status::CODE_OK,$this->getPagingDatas(0,[],0),'success');
		}

		foreach ($hits as $k => $h) {
			$source = $h['_source'];
			$resData[] = [
				'id' => $h['_id'],
				'name' => $source['name'],
				'image' => $source['image'],
				'uploader' => $source['uploader'],
				'create_time' => '',
				'duration' => '',
				'keywords' => [$keyword]
			];
		}
		//var_dump($resData);
		return $this->writeJson(Status::CODE_OK,$this->getPagingDatas($total,$resData,0),'success');
	}
}
