<?php

namespace common\models;

use common\models\relations\EventTest;
use common\models\relations\TestQuestion;
use common\models\relations\TestTheme;
use common\models\results\TestResults;
use common\models\relations\Event;
use common\traits\TagsTrait;
use common\traits\UpdateInsteadOfDeleteTrait;
use Yii;

/**
 * This is the model class for table "tests".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $user_id
 * @property integer $ts
 * @property string $info
 * @property integer $qcount
 * @property integer $time
 * @property integer $protected
 * @property integer $random
 * @property integer $type
 * @property integer $is_repeatable
 */
class Tests extends \common\components\ActiveRecord
{

    use TagsTrait, UpdateInsteadOfDeleteTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'qcount', 'time'], 'required'],
            [['user_id', 'qcount', 'time', 'type'], 'integer'],
            [['tagsString'], 'string'],
            [['name'], 'string', 'max' => 1000],
            [['description'], 'string', 'max' => 5000],
            [['protected','random', 'is_repeatable'], 'safe']
        ];
    }

    public function getQuestions()
    {
        return $this->hasMany(TestQuestion::className(), ["target_id"=>"id"]);
    }

    public function getThemes()
    {
        return $this->hasMany(TestTheme::className(), ["target_id"=>"id"]);
    }


    public function getQCount()
    {
        return $this->getQuestions()->count();
    }

    public function getResults()
    {
        return $this->hasMany(TestResults::className(), ['test_id' => 'id']);
    }

    /**
     * Возвращает модель TestResults -
     * результат выполнения теста, привязанного к определенному уроку, текущего пользвателя
     *
     * @param From $from
     * @return array|TestResults|\yii\db\ActiveRecord
     */
    public function getMyResult($from)
    {
        return $this->getResults()
            ->byUser()
            ->andWhere([
                'from' => $from->name,
                'from_id' => $from->id,
            ])
            ->one();
    }

    public function isOwner()
    {
        return $this->user_id == \Yii::$app->user->id;
    }

    public function getCanEdit()
    {
        return $this->isOwner();
    }

    public function getEvents()
    {
        return $this->hasMany(EventTest::className(), ['related_id' => 'id']);
    }

}
