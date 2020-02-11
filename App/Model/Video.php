<?php 
namespace App\Model;

/**
 * 	视频模型
 */
class Video extends Base
{
	public $tableName = 'video';

	/**
	 *	获取点播首页数据
	 */
	public function getVideoData($condition=[],$page=1,$size=10)
	{
		if(!empty($condition['cate_id'])){
			$this->db->where('cate_id',$condition['cate_id']);
		}
		if(!empty($size)){
			$this->db->pageLimit = $size;
		}

		// 经过审核处理的视频和最新发布的视频
		$this->db->where('status',\Yaconf::get('video_status.normal'));
		$this->db->orderBy('id','desc');
		
		$result = $this->db->paginate($this->tableName,$page);
		
		$data = [
			'total_pages' => (int)$this->db->totalPages,
			'page_size' => (int)$size,
			'count' => (int)$this->db->totalCount,
			'lists' => $result,
		];
		//var_dump($this->db->getLastQuery());
		return $data;
	}


	/**
	 *	获取点播首页缓存数据
	 */
	public function getVideoCacheData($condition=[],$page=1,$size=1000)
	{
		if(!empty($condition['cate_id'])){
			$this->db->where('cate_id',$condition['cate_id']);
		}
		if(!empty($size)){
			$this->db->pageLimit = $size;
		}

		// 经过审核处理的视频和最新发布的视频
		$this->db->where('status',\Yaconf::get('video_status.normal'));
		$this->db->orderBy('id','desc');
		
		$result = $this->db->paginate($this->tableName,$page);
		
		return $result;
	}
}