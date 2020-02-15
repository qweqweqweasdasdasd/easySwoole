<?php 
namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;


/**
 * 分类接口控制
 * http://150.109.46.180:9501/Category/index
 */
class Category extends Controller
{
	public function index()
	{
		$data = [
			'id' => 1,
			'name' => 'ulimit -n',
		];

		return $this->writeJson(200,$data,'success');
		//return $this->response()->write('Category');
	}
}
