<?php
namespace App\Websocket;
 
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;
 
class MessageHandler implements MessageComponentInterface
{
 
    protected $connections;
 
    public function __construct()
    {
        $this->connections = new SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->connections->attach($conn);
    }
 
    public function onMessage(ConnectionInterface $from, $msg)
{
    foreach ($this->connections as $connection) {
        if($connection === $from)
        {
            continue;
        }
        $connection->send($msg);
    }

    // Also, send the message back to the sender
    
}
 
    public function onClose(ConnectionInterface $conn)
    {
        $this->connections->detach($conn);
    }
 
    public function onError(ConnectionInterface $conn, Exception $e)
    {
 
        $this->connections->detach($conn);
        $conn->close();
    }
}