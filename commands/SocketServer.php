<?php

namespace app\commands;


class SocketServer extends BaseServer{

    /**
     * @var array
     */
    protected $subscribedTopics = array();

    /**
     * @param $data
     */
    public static function sendData(array $data) {
        
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'mail push');
        $socket->connect("tcp://127.0.0.1:5555");
        $socket->send(json_encode($data));
    }

    /**
     * @param $entry
     */
    public function broadcast($entry) {
        $entryData = json_decode($entry, true);

        if (!array_key_exists($entryData['topic_id'], $this->subscribedTopics)) {
            return;
        }

        $topic = $this->subscribedTopics[$entryData['topic_id']];

        $topic->broadcast($entryData);
    }
}