<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 17.02.2018
 * Time: 1:37
 */

namespace common\components;


use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use yii\base\Component;

class Rabbit extends Component
{

    public $host = null;
    public $port = null;
    public $user = null;
    public $password = null;

    public $connection = null;
    public $channel = null;

    public $queue_name = 'sdot_rabbit_queue';

    public function init()
    {
        $this->connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->password);
        $this->channel = $this->connection->channel();
    }

    public function declareQueue()
    {
        $this->channel->queue_declare($this->queue_name, false, true, false, false);
    }

    public function addToQueue($model)
    {
        $msg = new AMQPMessage(json_encode([
            'class' => $model::className(),
            'attributes' => $model->toArray()
        ]), ['delivery_mode' => 2]);
        $this->declareQueue();
        $this->channel->basic_publish($msg, '', $this->queue_name);
    }

}