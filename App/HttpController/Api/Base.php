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
    	$this->getParams();
        return true;
    }

    /** 
     *	获取request对象的数据，处理分页数据
     */	
    public function getParams()
    {
    	$params = $this->request()->getRequestParam();
		$params['page'] = !empty($params['page'])?intval($params['page']):1;
		$params['size'] = !empty($params['size'])?intval($params['size']):5;

        $params['from'] = $params['size'] * ($params['page'] - 1);
		$this->params = $params;
    }

    /**
     *  PHP数组分页处理
     */
    public function getPagingDatas($count,$data)
    {
        $totalPages = ceil($count/$this->params['size']);

        $data = $data ?? [];
        $data = array_splice($data, $this->params['from'], $this->params['size']);

        return [
            'total_pages' => (int)$totalPages,
            'page_size' => (int)$this->params['size'],
            'count' => (int)$count,
            'lists' => $data,
        ];
    }

    /**
     *	异常处理
     */
    // protected function onException(\Throwable $throwable,$actionName):void
    // {
    // 	$this->writeJson(400,'请求内部异常！');
    // }
}
