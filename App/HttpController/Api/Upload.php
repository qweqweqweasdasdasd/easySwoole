<?php 
namespace App\HttpController\Api;

use \EasySwoole\Core\Component\Di;
use App\HttpController\Api\Base;
use App\Lib\ClassArr;

/**
 * 上传视频和图片 接口
 */
class Upload extends Base
{
	/**
	 *	http://150.109.46.180:9501/api/upload/file
	 *	http://150.109.46.180:9501/Upload/Videos/1547808259.mp4
	 */
	public function file()
	{
		$request = $this->request();
		$files = $request->getSwooleRequest()->files;
		$types = array_keys($files);
		$type  = $types[0];

		try {
			//$obj = new Video($request);
			//$obj = new \App\Lib\Upload\Image($request);
			$classObj = new ClassArr();
			$classSata = $classObj->uploadClassStat();
			$uploadObj = $classObj->initClass($type,$classSata,[$request,$type]);

			$file = $uploadObj->upload();
		} catch (\Exception $e) {
			return $this->writeJson(400,[],'fail');
		}
		if(empty($file)){
			return $this->writeJson(400,[],'上传失败');
		}
		$data = [
			'url' => $file
		];
		return $this->writeJson(200,'success',$data);
	}
}
