<?php
namespace common\models;

use common\traits\AttributesToInfoTrait;
use common\traits\UpdateInsteadOfDeleteTrait;
use Yii;

/**
 * This is the model class for table "education_plans".
 *
 * @property int $id
 * @property string $name
 * @property int $organization_id
 * @property int $user_id
 * @property string $begin_ts
 * @property string $end_ts
 * @property string $info
 * @property string $ts
 * @property int $is_deleted
 */
class EducationPlans extends \common\components\ActiveRecord
{

    use AttributesToInfoTrait, UpdateInsteadOfDeleteTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'education_plans';
    }

    public function attributesToInfo()
    {
        return ['description', 'pupils_planned', 'events_planned'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'begin_ts', 'end_ts'], 'required'],
            [['begin_ts', 'end_ts'], 'safe'],
            [['info'], 'safe'],
            [['name'], 'string', 'max' => 500],
            [['description'], 'string', 'max' => 2000],
            [['pupils_planned','events_planned'], 'integer', 'min' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'name' => \Yii::t('main', 'Наименование'),
            'begin_ts' => \Yii::t('main', 'Дата начала'),
            'begin_ts_display' => \Yii::t('main', 'Дата начала'),
            'end_ts' => \Yii::t('main', 'Дата окончания'),
            'end_ts_display' => \Yii::t('main', 'Дата окончания'),
            'pupils_planned' => \Yii::t("main","Запланировано слушателей"),
            'events_planned' => \Yii::t("main","Запланировано мероприятий"),
            'info' => Yii::t('main', 'Info'),
            'ts' => Yii::t('main', 'Ts'),
            'is_deleted' => Yii::t('main', 'Is Deleted'),
        ];
    }

    public function getOwner()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    public function getCanEdit()
    {
        return \Yii::$app->user->can("specialist");
    }

    public function getBegin_ts_display()
    {
        return $this->begin_ts ? (new \DateTime($this->begin_ts))->format("d.m.Y") : "";
    }

    public function getEnd_ts_display()
    {
        return $this->end_ts ? (new \DateTime($this->end_ts))->format("d.m.Y") : "";
    }

}
