<?php 
namespace App\Lib\Upload;

use App\Lib\Util\Tools;

/**
 * 上传文件基类
 */
class Base
{
	/**
	 *	上传文件的类型
	 */
	public $type = '';

	public function __construct($request,$type = null)
	{
		$this->request = $request;
		if(empty($type)){
			$files = $this->request->getSwooleRequest()->files;
			$types = array_keys($files);
			$this->type  = $types[0];
		} else {
			$this->type = $type;
		}

	}

	/**
	 *	文件上传
	 */
	public function upload()
	{
		if($this->type != $this->fileType){
			throw new \Exception("上传文件的类型不对！");
		}

		$uploader = $this->request->getUploadedFile($this->type);

		// 文件大小检测
		$this->size = $uploader->getSize();
		// 获取上传文件的名称
		$fileName = $uploader->getClientFilename();
		// 获取到mediaType
		$this->mediaType = $uploader->getClientMediaType();
		// 检测mediaType
		$this->checkMediaType();

		// 获取到文件存储路径
		$file = $this->getFile($fileName);

		// 使用moveTo 方法上传文件
		$falg = $uploader->moveTo($file);

		if(!empty($falg)){
			return $this->file;
		}
		return false;
	}

	/**
	 *	获取到文件存储路径
	 */
	public function getFile($fileName)
	{
		$pathinfo = pathinfo($fileName);
		$extension = $pathinfo['extension'];

		$dirname = "/" . $this->type . "/" . date('Y') . "/" . date('m');
		$dir = EASYSWOOLE_ROOT . "/webroot" . $dirname;
		Tools::mk_dir($dir);

		$baseName = '/' . md5($fileName) . '.' . $extension;

		$this->file = $dirname . $baseName;
		return $dir . $baseName;
	}

	/**
	 *	mediaType文件检测
	 */
	public function checkMediaType()
	{
		$mediaType = explode('/', $this->mediaType);
		$mediaType = $mediaType[1] ?? "";
		if(empty($mediaType)){
			throw new \Exception("文件{$mediaType}不合法");
		}
		if(!in_array($mediaType, $this->mediaTypes)){
			throw new \Exception("文件{$mediaType}不合法");
		}
		return true;
	}

	/**
	 *	文件大小检测
	 */
	public function checkSize()
	{
		if(empty($this->size)){
			throw new \Exception("上传文件不得为空！");
		}
		return true;
	}
}