<?php
namespace App\Lib;

/**
 * 反射机制
 */
class ClassArr
{
	public function uploadClassStat()
	{
		return [
			'image' => "\App\Lib\Upload\Image",
			'video' => "\App\Lib\Upload\Video"
		];
	}

	public function initClass($type,$supportedClass,$params = [],$needInstance = true)
	{
		if(!array_key_exists($type, $supportedClass)){
			return false;
		}
		$calssName = $supportedClass[$type];

		return $needInstance ? (new \ReflectionClass($calssName))->newInstanceArgs($params): $calssName; 
	}	
}