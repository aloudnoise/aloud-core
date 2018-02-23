<?php

namespace common\models\counters;

use common\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "counters.counters_template".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $record_id
 * @property string $table
 * @property string $info
 * @property integer $ts
 * @property integer $state
 */
class CountersTemplate extends ActiveRecord
{

    public $count = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'counters.counters_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'record_id', 'table'], 'required'],
            [['user_id', 'record_id', 'ts', 'state'], 'integer'],
            [['table', 'info'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'record_id' => 'Record ID',
            'table' => 'Table',
            'info' => 'Info',
            'ts' => 'Ts',
            'state' => 'State',
        ];
    }


    /**
     * @param $model
     */
    public static function add($model) {
        $class = get_called_class();
        if ($class) {

            $string = $model->tableName()."_".$model->id."_".\Yii::$app->user->id;

            if (!\Yii::$app->cache->get($string)) {
                $check = $class::find()
                    ->andWhere([
                        "record_id" => $model->id,
                        "table" => $model->tableName(),
                    ])
                    ->byUser()
                    ->orderBy("ts DESC")
                    ->one();

                if ($check AND $check->ts > (time() - 300)) {
                    return;
                }

                $r = new $class();
                $r->record_id = $model->id;
                $r->table = $model->tableName();
                $r->user_id = \Yii::$app->user->id;
                $r->save();

                \Yii::$app->cache->set($string, '1', 300);

            }
        }
    }

}
