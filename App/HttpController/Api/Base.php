<?php 
namespace App\HttpController\Api;

use EasySwoole\Core\Http\AbstractInterface\Controller;

/**
 * api模块 基础类库
 */
class Base extends Controller
{
	/**
	 *	实现controller抽象index方法
	 */
	public function index()
	{
		return $this->response()->write('base index');
	}

	/**
	 *	拦截器
	 */
	protected function onRequest($action):?bool
    {
    	//$this->writeJson(403,'您没有权限访问！');
        return true;
    }

    /**
     *	异常处理
     */
    // protected function onException(\Throwable $throwable,$actionName):void
    // {
    // 	$this->writeJson(400,'请求内部异常！');
    // }
}
