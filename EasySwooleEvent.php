<?php
namespace EasySwoole\EasySwoole;


use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

use EasySwoole\EasySwoole\Crontab\Crontab;
use EasySwoole\Component\Di;
use App\Lib\Redis\Redis;
use App\Model\Es\EsClient;
use App\Lib\Process\Consumer;
use App\Lib\Process\ConsumerTest;
use App\Lib\Cache\Video as videoCache;
use EasySwoole\Component\Timer;
use EasySwoole\EasySwoole\ServerManager;

use App\Lib\Pool\MysqlPool;
use EasySwoole\Component\Pool\PoolManager;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.
        $dbConf = \Yaconf::get('mysql');
        Di::getInstance()->set('MYSQL',\MysqliDb::class,Array (
            'host' => $dbConf['host'],
            'username' => $dbConf['username'],
            'password' => $dbConf['password'],
            'db'=> $dbConf['db'],
            'port' => 3306,
            'charset' => $dbConf['charset'])
        );



        // 註冊redis对象
        Di::getInstance()->set('Redis',Redis::getInstance());
        Di::getInstance()->set('ES',EsClient::getInstance());
        // 註冊redis連接池
        // $redisPoolConfig = \EasySwoole\RedisPool\Redis::getInstance()->register('redis',new \EasySwoole\Redis\Config\RedisConfig());
        // //配置连接池连接数
        // $redisPoolConfig->setMinObjectNum(5);
        // $redisPoolConfig->setMaxObjectNum(20);

        // 注册多进程redis异步队列
        $allNum = 5;
        for ($i = 0 ;$i < $allNum;$i++){

            //ProcessManager::getInstance()->addProcess("consumer_{$i}",ConsumerTest::class);

            ServerManager::getInstance()->getSwooleServer()->addProcess((new ConsumerTest("consumer_{$i}"))->getProcess());
        }

        // CronTab 定时器定时生成静态化数据
        $videoCacheObj = new videoCache();
        // 方案 一
        // CronTab::getInstance()
        //         ->addRule('swoole_api_video_index_lists','*/10 * * * *',function() use($videoCacheObj){
        //             $videoCacheObj->setIndexVideo();
        //         })
        //         ->addRule('swoole_api_video_index_lists_2','*/20 * * * *',function(){
        //         var_dump('22222222');
        //         });

        // 方案二
        // swoole 毫秒级的定时器
        $register->add(EventRegister::onWorkerStart,function(\swoole_server $server, $workerId) use($videoCacheObj){
            Timer::getInstance()->loop(1000*2,function() use($videoCacheObj,$workerId){
                if($workerId == 1){
                    echo 'workerId '.$workerId.PHP_EOL;
                    $videoCacheObj->setIndexVideo();
                }
            });
        });
        
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}