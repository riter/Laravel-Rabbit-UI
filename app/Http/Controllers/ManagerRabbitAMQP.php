<?php
/**
 * Created by PhpStorm.
 * User: RiterCordova
 * Date: 12/10/18
 * Time: 15:32
 */

use App\Http\Middleware\AMQPMiddleware;
use App\Notification;
use function foo\func;
use http\Env\Request;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPIOException;
use PhpAmqpLib\Message\AMQPMessage;


const WAIT_BEFORE_RECONNECT_uS = 1000000;

 const host = "127.0.0.1";
 const port = 5672;
 const user = "guest";
 const passw = "guest";
 const vhost = '/';
 const exchange = "riter-storage";
 const queue = "mailing";


$connection = null;

//function connect() {
//    $connection = new AMQPStreamConnection(
//        host,
//        port,
//        user,
//        passw,
//        vhost,
//        false,
//        'AMQPLAIN',
//        null,
//        'en_US',
//        3.0,
//        3.0, //3.0
//        null,
//        true,
//        0.0
//    );
//    return $connection;
//}

/*
    To handle arbitrary node restart you can use a combination of connection
    recovery and mulltiple hosts connection.
*/
function connect() {
    // If you want a better load-balancing, you cann reshuffle the list.
    return AMQPStreamConnection::create_connection([
        ['host' => host, 'port' => port, 'user' => user, 'password' => passw, 'vhost' => vhost],
    ],
        [
            'insist' => false,
            'login_method' => 'AMQPLAIN',
            'login_response' => null,
            'locale' => 'en_US',
            'connection_timeout' => 3.0,
            'read_write_timeout' => 3.0,
            'context' => null,
            'keepalive' => false,
            'heartbeat' => 0
        ]);
}

function cleanup_connection($connection) {
    // Connection might already be closed.
    // Ignoring exceptions.
    try {
        if($connection !== null) {
            $connection->close();
        }
    } catch (\ErrorException $e) {
    }
}

while(true){
    try {
        $connection = connect();
        register_shutdown_function('shutdown', $connection);
        // Your application code goes here.
        do_something_with_connection($connection);
    } catch(AMQPIOException $e) {
        echo "AMQP IO exception " . PHP_EOL;
        cleanup_connection($connection);
        usleep(WAIT_BEFORE_RECONNECT_uS);
    } catch(\RuntimeException $e) {
        echo "Runtime exception " . PHP_EOL;
        cleanup_connection($connection);
        usleep(WAIT_BEFORE_RECONNECT_uS);
    } catch(\ErrorException $e) {
        echo "Error exception " . PHP_EOL;
        cleanup_connection($connection);
        usleep(WAIT_BEFORE_RECONNECT_uS);
    }
}
function do_something_with_connection($connection) {
    $exchange = "riter-storage";
    $queue = 'mailing';
    $consumerTag = 'consumer';
    $channel = $connection->channel();
    $channel->queue_declare($queue, false, true, false, false);

    $channel->exchange_declare($exchange, "direct", false, true, false);
    $channel->queue_bind($queue, $exchange);

    $channel->basic_consume($queue, $consumerTag, false, false, false, false, 'process_message');
    while (count($channel->callbacks)) {
        $channel->wait();
    }
}
/**
 * @param \PhpAmqpLib\Message\AMQPMessage $message
 */
function process_message($message)
{
    echo "\n--------\n";
    echo $message->body;
    echo "\n--------\n";
    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    // Send a message with the string "quit" to cancel the consumer.
    if ($message->body === 'quit') {
        $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
    }
}
/**
 * @param \PhpAmqpLib\Connection\AbstractConnection $connection
 */
function shutdown($connection)
{
    $connection->close();
}