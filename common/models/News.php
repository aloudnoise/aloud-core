<?php

namespace common\models;

use common\components\ActiveRecord;
use common\traits\AttributesToInfoTrait;
use common\traits\CounterTrait;
use common\traits\DateFormatTrait;
use common\traits\TagsTrait;
use common\traits\UpdateInsteadOfDeleteTrait;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property int $organization_id
 * @property string $name           - название
 * @property string $image          - картинка
 * @property string $content        - текст
 * @property int $type              - тип
 * @property int $user_id           - автор
 * @property int $is_deleted
 * @property integer $ts             - дата
 * @property string $info
 */
class News extends ActiveRecord
{
    use UpdateInsteadOfDeleteTrait, AttributesToInfoTrait, TagsTrait, CounterTrait, DateFormatTrait;

    public static function tableName()
    {
        return 'news';
    }

    public function rules()
    {
        return [
            [['name', 'tagsString'], 'required'],
            [['info'], 'safe'],
            [['type', 'user_id'], 'integer'],
            [['content', 'tagsString'], 'string'],
            [['name', 'image'], 'string', 'max' => 255],
        ];
    }

    public function getAuthor()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    public function getCanView()
    {
        return true;
    }

    public function getCanEdit()
    {
        return $this->user_id == \Yii::$app->user->id OR \Yii::$app->user->can("admin");
    }

    public function getShortContent()
    {
        $s = $this->content;
        $s = strip_tags($s);
        return (mb_strlen($s, "UTF-8")>350 ? mb_substr($s, 0, 347, "UTF-8")."..." : $s);
    }
}
