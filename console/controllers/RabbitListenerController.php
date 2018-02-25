<?php
namespace aloud_core\console\controllers;

use aloud_core\common\components\Rabbit;
use yii\console\Controller;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitListenerController extends Controller
{

    public function actionListen()
    {

        /* @var $rabbit Rabbit */
        $rabbit = \Yii::$app->rabbit;
        $rabbit->declareQueue();

        $channel = $rabbit->channel;

        $channel->basic_qos(
            null,
            1,
            null
        );

        $channel->basic_consume(
            $rabbit->queue_name,
            '',
            false,
            false,
            false,
            false,
            array($this, 'process')
        );

        while(count($channel->callbacks)) {
            echo "WAITING \n";
            $channel->wait();
        }

        $channel->close();
        $rabbit->connection->close();
    }

    /**
     * обработка полученного запроса
     *
     * @param AMQPMessage $msg
     */
    public function process(AMQPMessage $msg)
    {

        $data = json_decode($msg->getBody(), true);

        $model = new $data['class']();
        $model->attributes = $data['attributes'];
        $model->save();

        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

}