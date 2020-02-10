<?php 
namespace App\Lib\Upload;

use App\Lib\Upload\Base;

/**
 * 上传视频
 */
class Video extends Base
{
	/**
	 *	定义一个文件类型
	 */	
	public $fileType = 'video';
	
	/**
	 *	文件大小
	 */
	public $maxSize = 122;

	/**
	 *	允许上传的文件类型数组
	 */
	public $mediaTypes = [
		'mp4',
	];
}