<?php
namespace app\models;


class Chats extends \common\models\Chats
{

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'organization_id' => 'Organization ID',
            'name' => 'Name',
            'type' => 'Type',
            'user_id' => 'User ID',
            'ts' => 'Ts',
            'info' => 'Info',
        ];
    }


}