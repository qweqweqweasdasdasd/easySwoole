<?php 
namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;

/**
 * 测试接口控制
 */
class Index extends Controller
{
	public function index()
	{
		return $this->response()->write('swoole');
	}
}
