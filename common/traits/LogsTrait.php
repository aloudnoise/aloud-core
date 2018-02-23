<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 18.02.2018
 * Time: 21:52
 */

namespace common\traits;


trait LogsTrait
{
    use AttributesToInfoTrait;

    public function attributesToInfo()
    {
        return ['attributes', 'model', 'referer', 'user_agent'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'organization_id'], 'integer'],
            [['info', 'type'], 'safe'],
            [['route'], 'string', 'max' => 3000],
            [['ip'], 'string', 'max' => 100],
        ];
    }

}