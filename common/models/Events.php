<?php

namespace common\models;

use common\behaviors\DynamicSelectBehavior;
use common\components\ActiveRecord;

use common\models\relations\EventCourse;
use common\models\relations\EventMaterial;
use common\models\relations\EventOrganization;
use common\models\relations\EventTask;
use common\models\relations\EventTest;
use common\models\relations\EventTheme;
use common\models\relations\EventUser;
use common\queries\EventsQuery;
use common\traits\AttributesToInfoTrait;
use common\traits\DateFormatTrait;
use common\traits\TagsTrait;
use common\traits\UpdateInsteadOfDeleteTrait;
use Yii;

/**
 * This is the model class for table "events".
 *
 * @property integer $id
 * @property string $name
 * @property string $date
 * @property integer $user_id
 * @property integer $state
 * @property integer $begin_ts
 * @property integer $end_ts
 * @property string $info
 */
class Events extends ActiveRecord
{
    use UpdateInsteadOfDeleteTrait, AttributesToInfoTrait, TagsTrait, DateFormatTrait;

    const STATE_SHARED = 2;

    const PARTICIPATION_STATUS_ACTIVE = 1;
    const PARTICIPATION_STATUS_NOT_BEGUN = 2;
    const PARTICIPATION_STATUS_EXPIRED = 3;

    /**
     * @inheritdoc
     */
    protected static $UPDATE_ON_DELETE = true;

    public static function tableName()
    {
        return 'events';
    }

    public static function find()
    {
        return (new EventsQuery(get_called_class()))->notDeleted();
    }

    public function attributesToInfo()
    {
        return ['description', 'education_view', 'subject_id'];
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'themesBehavior' => [
                'class' => DynamicSelectBehavior::className(),
                'select_attributes' => [
                    'subject' => [
                        'target_attribute' => 'subject_id',
                        'instance' => function($value) {
                            $model = new DicValues();
                            $model->name = $value;
                            $model->dic = 'subjects';
                            return $model;
                        }
                    ]
                ]
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'begin_ts', 'end_ts'], 'required'],
            [['tagsString'], 'string'],
            [['begin_ts'], function() {
                $ts = (new \DateTime($this->begin_ts))->getTimestamp();
                if ($ts < mktime(0,0,0,date('m'),date('d'),date('Y'))) {
                    $this->addError("begin_ts", \Yii::t("main","Дата начала не может быть меньше текущей даты"));
                    return false;
                }
                return true;
            }, 'when' => function() {
                return $this->isNewRecord;
            }],
            [['end_ts'], function() {
                $ts = (new \DateTime($this->end_ts))->getTimestamp();
                $start_ts = (new \DateTime($this->begin_ts))->getTimestamp();

                if ($ts < $start_ts) {
                    $this->addError("end_ts", \Yii::t("main","Дата окончания не может быть меньше даты начала"));
                    return false;
                }

                if ($this->isNewRecord) {
                    if ($ts < mktime(0, 0, 0, date('m'), date('m'), date('Y'))) {
                        $this->addError("end_ts", \Yii::t("main", "Дата окончания не может быть меньше текущей даты"));
                        return false;
                    }
                }

                return true;
            }],
            [['name'], 'string'],
            [['description'], 'string', 'max' => 2000],
            [['subject'], 'string', 'max' => 150],
            [['user_id', 'state'],  'number', 'integerOnly'=>true],
            [['education_view', 'subject_id'], 'integer']

        ];
    }



    public function getBegin_ts_display()
    {
        return $this->begin_ts ? (new \DateTime($this->begin_ts))->format("d.m.Y") : "";
    }

    public function getEnd_ts_display()
    {
        return $this->end_ts ? (new \DateTime($this->end_ts))->format("d.m.Y") : "";
    }

    public function getUsers()
    {
        return $this->hasMany(EventUser::className(), ["target_id" => "id"]);
    }

    public function getCourses()
    {
        return $this->hasMany(EventCourse::className(), ["target_id" => "id"]);
    }

    public function getTests()
    {
        return $this->hasMany(EventTest::className(), ["target_id" => "id"])->orderBy([
            'event_test.ts' => 'DESC'
        ]);
    }

    public function getTasks()
    {
        return $this->hasMany(EventTask::className(), ["target_id" => "id"])->orderBy([
            'event_task.ts' => 'DESC'
        ]);
    }

    public function getThemes()
    {
        return $this->hasMany(EventTheme::className(), ['target_id' => 'id'])->orderBy([
            'event_theme.ts' => 'DESC'
        ]);
    }

    public function getMaterials()
    {
        return $this->hasMany(EventMaterial::className(), ["target_id" => "id"]);
    }

    public function getOrganizations()
    {
        return $this->hasMany(EventOrganization::className(), ["target_id" => "id"]);
    }


    public function getMember()
    {
        return $this->hasOne(EventUser::className(), ["target_id" => "id"])->onCondition([
            "event_user.related_id" => \Yii::$app->user->id
        ]);
    }

    public function getOwner()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    private $_is_participant = null;
    public function getIsParticipant()
    {
        if ($this->_is_participant === null) {
            $this->_is_participant = ($this->member OR $this->state == Events::STATE_SHARED) ? true : false;
        }
        return $this->_is_participant;
    }

    public function getCanView()
    {
        return $this->user_id == \Yii::$app->user->id OR \Yii::$app->user->can("admin") OR $this->isParticipant OR $this->state == Events::STATE_SHARED;
    }

    public function getCanEdit()
    {
        return $this->user_id == \Yii::$app->user->id OR \Yii::$app->user->can("admin");
    }

    public function getShortDescription()
    {
        $s = $this->description;
        return (mb_strlen($s, "UTF-8")>200 ? mb_substr($s, 0, 197, "UTF-8")."..." : $s);
    }

    public function getParticipation()
    {
        $start = (new \DateTime($this->begin_ts))->getTimestamp();
        $end = (new \DateTime($this->end_ts))->getTimestamp() + 86400;

        if ($start > time()) {
            return [
                'status' => static::PARTICIPATION_STATUS_NOT_BEGUN,
                'text' => \Yii::t("main","Мероприятие еще не началось"),
                'color' => 'info'
            ];
        }

        if ($end < time()) {
            return [
                'status' => static::PARTICIPATION_STATUS_EXPIRED,
                'text' => \Yii::t("main","Мероприятие завершено"),
                'color' => 'warning'
            ];
        }

        return [
            'status' => static::PARTICIPATION_STATUS_ACTIVE
        ];

    }

    public function getSubject()
    {
        return DicValues::fromDic($this->subject_id);
    }

}
