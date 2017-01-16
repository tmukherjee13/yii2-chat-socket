<?php
namespace tmukherjee13\chatter\console\controllers;

use tmukherjee13\chatter\Chat;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use yii\console\Controller;

class ChatServerController extends Controller
{

    public function actionStart()
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Chat()
                )
            ),
            8080
        );

        print("Starting chat server...");
        echo PHP_EOL;
        print("Chat server running on port 8080");
        echo PHP_EOL;
        $server->run();
    }

}
