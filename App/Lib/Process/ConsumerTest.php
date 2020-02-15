<?php 
namespace App\Lib\Process;

use EasySwoole\Component\Process\AbstractProcess;
use Swoole\Process;
use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\Logger;
use App\Lib\Redis\Redis;

class ConsumerTest extends AbstractProcess
{
    private $isRun = false;
    public function run($arg)
    {
        // TODO: Implement run() method.
        /*
         * 举例，消费redis中的队列数据
         * 定时500ms检测有没有任务，有的话就while死循环执行
         */
        $this->addTick(500,function (){
            if(!$this->isRun){
                $this->isRun = true;
                //$redis = new \redis();//此处为伪代码，请自己建立连接或者维护redis连接
                
                while (true){
                    try{
                        //$task = Di::getInstance()->get('Redis')->lPop('swoole_list_test');
                        $task = (new Redis)->lPop('swoole_list_test');
                        
                        //获取连接池对象
                        // $redisPool = \EasySwoole\RedisPool\Redis::getInstance()->get('redis');
                        // $redis = $redisPool->getObj();
                        // $task = $redis->exec('lpop','swoole_list_test');
                        // $pool->freeObj($redis);
                        if($task){
                            // do you task
                        	// 发送邮件 推送信息 视频喜欢
                        	Logger::getInstance()->log($this->getProcessName().'---'.$task);
                        }else{
                            break;
                        }
                    }catch (\Throwable $throwable){
                        break;
                    }
                }
                $this->isRun = false;
            }
            //var_dump($this->getProcessName().' task run check');
        });
    }
    public function onShutDown()
    {
        // TODO: Implement onShutDown() method.
    }
    public function onReceive(string $str, ...$args)
    {
        // TODO: Implement onReceive() method.
    }
}