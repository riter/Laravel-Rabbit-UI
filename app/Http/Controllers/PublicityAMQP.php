<?php
/**
 * Created by PhpStorm.
 * User: RiterCordova
 * Date: 12/10/18
 * Time: 17:38
 */

namespace App\Http\Controllers;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class PublicityAMQP
{

    private $host = "127.0.0.1";
    private $port = 5672;
    private $user = "guest";
    private $passw = "guest";
    private $vhost = '/';
    private $exchange = "riter-storage";
    private $queue = "storage-request";

    private $connection = null;
    private $channel = null;

    function publisher(array $data) {

        $this->connection = new AMQPStreamConnection(
            $this->host,
            $this->port,
            $this->user,
            $this->passw,
            $this->vhost,
            false,
            'AMQPLAIN',
            null,
            'en_US',
            3.0,
            3.0, //3.0
            null,
            true,
            0.0
        );
        $this->channel = $this->connection->channel();

        $this->channel->queue_declare($this->queue, false, true, false, false);
        $this->channel->exchange_declare($this->exchange, "direct", false, true, false);
        $this->channel->queue_bind($this->queue, $this->exchange);

        $messageBody = json_encode($data);

        $message = new AMQPMessage($messageBody, [
            "content_type" => "application/json",
            "delivery_mode" => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $this->channel->basic_publish($message, $this->exchange);
        $this->channel->close();
        $this->connection->close();
    }

}