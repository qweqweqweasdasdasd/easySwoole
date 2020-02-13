// 创建索引
{
  "mappings": {
  	"video":{
    "properties": {
      "name": {
        "type": "text"
      },
      "cate_id": {
        "type": "integer"
      },
      "image": {
        "type": "text"
      },
      "url": {
        "type": "text"
      },
      "type": {
        "type": "byte"
      },
      "content": {
        "type": "text"
      },
      "uploader": {
        "type": "keyword"
      },
      "create_time": {
        "type": "integer"
      },
      "update_time": {
        "type": "integer"
      },
      "status": {
        "type": "byte"
      },
      "video_id": {
        "type": "keyword"
      },
      "duration": {
        "type": "integer"
      }
    }
  }
}
}
// 输入一条数据
{
  "name": "刘德华",
  "cate_id": 1,
  "image": "http://www.baidu.com",
  "url": "http://www.g.com",
  "type": 1,
  "uploader": "sinwo",
  "status": 1,
  "video_id": "234qweqwe"
}
// 查询一条数据 [模糊匹配] match [精确匹配] match_phrase
{
  "query": {
    "match": {
      "name": "刘德华"
    }
  }
}
