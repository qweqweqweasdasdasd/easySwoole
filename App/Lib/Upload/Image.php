<?php 
namespace App\Lib\Upload;

use App\Lib\Upload\Base;

/**
 * 上传视频
 */
class Image extends Base
{
	/**
	 *	定义一个文件类型
	 */	
	public $fileType = 'image';
	
	/**
	 *	文件大小
	 */
	public $maxSize = 122;

	/**
	 *	允许上传的文件类型数组
	 */
	public $mediaTypes = [
		'jpeg',
		'png',
		'gif',
	];
}