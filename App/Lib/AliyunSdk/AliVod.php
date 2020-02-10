<?php 
namespace App\Lib\AliyunSdk;

require_once EASYSWOOLE_ROOT . '/App/Lib/AliyunSdk/aliyun-php-sdk-core/Config.php';   // 假定您的源码文件和aliyun-php-sdk处于同一目录。
require_once EASYSWOOLE_ROOT . '/App/Lib/AliyunSdk/aliyun-oss-php-sdk-master/autoload.php';

use vod\Request\V20170321 as vod;
use OSS\OssClient;
use OSS\Core\OssException;

/**
 *	阿里云点播上传视频
 */
class AliVod 
{
	/**
	 *	点播服务所在的Region，国内请填cn-shanghai，不要填写别的区域
	 */
	public $regionId = 'cn-shanghai';

	/**
	 *	vod 客服端对象
	 */
	public $client = '';

	/**
	 *	OSS 上传对象
	 */
	public $ossClient = '';

	/**
	 *	使用AK初始化VOD客户端
	 */
	public function __construct()
	{
	    $profile = \DefaultProfile::getProfile($this->regionId, \Yaconf::get('aliyun_vod.accessKeyId'), \Yaconf::get('aliyun_vod.accessKeySecret'));
	    $this->client = new \DefaultAcsClient($profile);
	}

	/**
	 *	获取视频上传地址和凭证
	 */
	public function createUploadVideo($title,$videoFileName,$other = [])
	{
		$request = new vod\CreateUploadVideoRequest();

	    $request->setTitle($title);        // 视频标题(必填参数)
	    $request->setFileName($videoFileName); // 视频源文件名称，必须包含扩展名(必填参数)
	    
	    if(!empty($other['desc'])){
	    	$request->setDescription($other['desc']);  // 视频源文件描述(可选)
	    }
	    if(!empty($other['url'])){
	    	$request->setCoverURL($other['url']); // 自定义视频封面(可选)
	    }
	    if(!empty($other['tags'])){
	    	$request->setTags($other['tags']); // 视频标签，多个用逗号分隔(可选)
	    }
	    $result = $this->client->getAcsResponse($request);
	    if(empty($result) || empty($result->UploadAddress)){
	    	throw new \Exception("获取视频上传地址和凭证失败");
	    }
	    return $result;
	}

	/**
	 *	 使用上传凭证和地址初始化OSS客户端（注意需要先Base64解码并Json Decode再传入）
	 */

	public function initOssClient($uploadAuth, $uploadAddress) {
	    $this->ossClient = new OssClient($uploadAuth['AccessKeyId'], $uploadAuth['AccessKeySecret'], $uploadAddress['Endpoint'], 
	        false, $uploadAuth['SecurityToken']);
	    $this->ossClient->setTimeout(86400*7);    // 设置请求超时时间，单位秒，默认是5184000秒, 建议不要设置太小，如果上传文件很大，消耗的时间会比较长
	    $this->ossClient->setConnectTimeout(10);  // 设置连接超时时间，单位秒，默认是10秒
	    return $this->ossClient;
	}

	/**
	 *	上传本地文件
	 */
	public function uploadLocalFile($uploadAddress, $localFile) {
	    return $THIS->ossClient->uploadFile($uploadAddress['Bucket'], $uploadAddress['FileName'], $localFile);
	}

	/**
	 *	通过ID获取到视频的播放地址
	 */
	public function getPlayInfo($videoId = 0)
	{
		if(empty($videoId)){
			return [];
		}

		$request = new vod\GetPlayInfoRequest();
		$request->setVideoId($videoId);
		$request->setAcceptFormat('JSON');
		return $this->getAcsResponse($request);
	}
}
