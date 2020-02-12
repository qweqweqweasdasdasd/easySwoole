<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/1/9
 * Time: 下午1:04
 */

namespace EasySwoole;

use \EasySwoole\Core\AbstractInterface\EventInterface;
use EasySwoole\Core\Swoole\Process\ProcessManager;
use EasySwoole\Core\Component\Crontab\CronTab;
use \EasySwoole\Core\Swoole\ServerManager;
use \EasySwoole\Core\Swoole\EventRegister;
use EasySwoole\Core\Swoole\Time\Timer;
use \EasySwoole\Core\Http\Response;
use \EasySwoole\Core\Http\Request;
use \EasySwoole\Core\Component\Di;
use App\Lib\Process\ConsumerTest;
use App\Lib\Cache\Video as videoCache;
use App\Lib\Redis\Redis;
use \EasySwoole\Config;
use \think\facade\Db;

Class EasySwooleEvent implements EventInterface {

    public static function frameInitialize(): void
    {
        // TODO: Implement frameInitialize() method.
        date_default_timezone_set('Asia/Shanghai');

        // // 获得数据库配置
        // $dbConf = Config::getInstance()->getConf('database');
        // // 全局初始化
        // Db::setConfig($dbConf);
    }

    public static function mainServerCreate(ServerManager $server,EventRegister $register): void
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
        // 註冊redis連接池
        // $redisPoolConfig = \EasySwoole\RedisPool\Redis::getInstance()->register('redis',new \EasySwoole\Redis\Config\RedisConfig());
        // //配置连接池连接数
        // $redisPoolConfig->setMinObjectNum(5);
        // $redisPoolConfig->setMaxObjectNum(20);

        // 注册多进程redis异步队列
        $allNum = 5;
        for ($i = 0 ;$i < $allNum;$i++){
            ProcessManager::getInstance()->addProcess("consumer_{$i}",ConsumerTest::class);
        }

        // CronTab 定时器定时生成静态化数据
        $videoCacheObj = new videoCache();
        // 方案 一
        CronTab::getInstance()
                ->addRule('swoole_api_video_index_lists','*/10 * * * *',function() use($videoCacheObj){
                    $videoCacheObj->setIndexVideo();
                })
                ->addRule('swoole_api_video_index_lists_2','*/20 * * * *',function(){
                var_dump('22222222');
                });

        // 方案二
        // swoole 毫秒级的定时器
        // $register->add(EventRegister::onWorkerStart,function(\swoole_server $server, $workerId) use($videoCacheObj){
        //     Timer::loop(1000*2,function() use($videoCacheObj,$workerId){
        //         if($workerId == 1){
        //             echo 'workerId '.$workerId.PHP_EOL;
        //             $videoCacheObj->setIndexVideo();
        //         }
        //     });
        // });
        
    }

    public static function onRequest(Request $request,Response $response): void
    {
        // TODO: Implement onRequest() method.
    }

    public static function afterAction(Request $request,Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}