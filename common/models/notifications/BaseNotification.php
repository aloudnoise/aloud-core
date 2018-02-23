<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 14.02.2018
 * Time: 18:39
 */

namespace common\models\notifications;


use common\models\Notifications;

/**
 * Class BaseNotification
 * @package common\models\notifications
 *
 * @property string $name
 * @property string $content
 * @property string $icon
 * @property string $color
 * @property string $url
 *
 */
class BaseNotification extends Notifications
{

    public function attributesToInfo()
    {
        return ['name', 'content', 'icon', 'url', 'action', 'color'];
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name', 'content', 'url', 'icon', 'color'], 'string'],
            [['action'], 'integer'],
            [['type'], 'filter', 'filter' => function() {
                return static::getType();
            }]
        ]);
    }

    public static function getType()
    {
        return static::TYPE_BASE;
    }

    public function fields()
    {
        return array_merge(parent::fields(), [
            'name' => 'name',
            'content' => 'content',
            'action' => 'action',
            'url' => 'url',
            'color' => 'color',
            'icon' => 'icon'
        ]);
    }

}