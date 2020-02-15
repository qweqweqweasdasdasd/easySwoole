<?php 
namespace App\Model\Es;

use \EasySwoole\Component\Di;

/**
 *  es 只关心搜索方法
 */
class EsVideo extends EsBase
{
	/** 
	 *	索引值
	 */
	public $index = 'es_video';

}