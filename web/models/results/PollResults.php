<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 08.01.2018
 * Time: 23:23
 */

namespace app\models\results;


class PollResults extends \common\models\results\PollResults
{

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'poll_id' => 'Poll ID',
            'user_id' => 'User ID',
            'result' => 'Result',
            'info' => 'Info',
            'ts' => 'Ts',
        ];
    }

}