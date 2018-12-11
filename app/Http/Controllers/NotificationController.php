<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AMQPMiddleware;
use App\Notification;
use function foo\func;
use http\Env\Request;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class NotificationController extends Controller
{
    private $host = "127.0.0.1";
    private $port = 5672;
    private $user = "guest";
    private $passw = "guest";
    private $vhost = '/';
    private $exchange = "riter-storage";
    private $queue = "mailing";

    private $channel;
    private $connection;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        include(app_path() . '/Http/Controllers/ManagerRabbitAMQP.php');

        $notification = Notification::all();
//        $this->publisher();
        return view('notification')->with("notifications", $notification);
    }



    function receiver() {
        $consumerTag = "userRiter";

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
            5.0
        );
        $this->channel = $this->connection->channel();

        $this->channel->queue_declare($this->queue, false, true, false, false);
        $this->channel->exchange_declare($this->exchange, "direct", false, true, false);
        $this->channel->queue_bind($this->queue, $this->exchange);

        $this->channel->basic_consume($this->queue, $consumerTag, false, false, false, false, function (AMQPMessage $message) {
            $messageBody = json_decode($message->body);

            info("entro");
            info($message->body);

            $message->delivery_info["channel"]->basic_ack($message->delivery_info["delivery_tag"]);
        });

        while(count($this->channel->callbacks)) {
            $this->channel->wait();
        }
        $this->channel->close();
        $this->connection->close();

//        Request::($this->channel, $this->connection);
    }
}

function process_message(AMQPMessage $message) {
    $messageBody = json_decode($message->body);

    info("entro");
    info($message->body);


    $message->delivery_info["channel"]->basic_ack($message->delivery_info["delivery_tag"]);

//        $this->channel->close();
//        $this->connection->close();
}

//var $channel;
//var $connection;
//
//register_shutdown_function("shutdown", $this->channel, $this->connection);
//
//function shutdown($channel, $connection) {
//    $channel->close();
//    $connection->close();
//}
//
//while(count($this->channel->callbacks)) {
//    $this->channel->wait();
//}