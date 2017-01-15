<?php
namespace app\commands;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use yii\console\Controller;
use React\ZMQ\Context;
use React\EventLoop\Factory;
use React\Socket\Server;
use Ratchet\Wamp\WampServer;

class SocketController extends Controller
{
    public function actionStart()
    {
var_dump(class_exists('ZMQContext'));
        $loop = Factory::create();
        $pusher = new SocketServer();


        $context = new Context($loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:5555');
        $pull->on('message', array($pusher, 'broadcast'));

        $webSock = new Server($loop);
        $webSock->listen(8080, '0.0.0.0');
        $webServer = new IoServer(
            new HttpServer(
                new WsServer(
                    new WampServer(
                        $pusher
                    )
                )
            ),
            $webSock
        );

        $loop->run();
    }
}