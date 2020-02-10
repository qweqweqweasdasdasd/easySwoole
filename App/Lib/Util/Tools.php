<?php 
namespace App\Lib\Util;


/**
 * Tools 工具类
 */
class Tools
{
	public static function mk_dir($targetPath)
	{
		if(!is_dir($targetPath)){
			mkdir($targetPath,0777,true);
		}
	}
}