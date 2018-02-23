<?php

namespace common\models;

use app\helpers\Url;
use app\models\relations\EventMaterial;
use app\widgets\EMaterial\EMaterial;
use common\behaviors\DynamicSelectBehavior;
use common\components\ActiveQuery;
use common\models\materials\Conferences;
use common\models\materials\Ders;
use common\models\materials\Files;
use common\models\materials\Links;
use common\models\materials\Videos;
use common\traits\AttributesToInfoTrait;
use common\traits\CounterTrait;
use common\traits\TagsTrait;
use common\traits\UpdateInsteadOfDeleteTrait;
use Yii;

/**
 * This is the model class for table "materials".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property string $info
 * @property integer $ts
 * @property integer $user_id
 * @property string $description
 * @property string $source
 * @property int $is_deleted
 * @property integer $theme_id
 * @property string $language
 * @property integer $is_shared
 * @property integer $access_by_link
 */
class Materials extends \common\components\ActiveRecord
{

    use UpdateInsteadOfDeleteTrait, CounterTrait, TagsTrait, AttributesToInfoTrait;

    const TYPE_LINK = 1;    // Ссылка
    const TYPE_VIDEO = 2;   // Видео
    const TYPE_FILE = 3;    // Файл
    const TYPE_DER = 4;     // ЦОР
    const TYPE_CONFERENCE = 5;  // Вебинар

    const SCENARIO_LIBRARY_SAVE = 'library_save';

    const MTYPE_TEXT = 1;
    const MTYPE_DOCUMENT = 2;
    const MTYPE_AUDIO = 3;
    const MTYPE_VIDEO = 4;
    const MTYPE_PRESENTATION = 5;
    const MTYPE_ARCHIVE = 6;
    const MTYPE_IMAGE = 7;
    const MTYPE_LINK = 8;
    const MTYPE_HTML = 9;

    public static function instantiate($row)
    {
        $models = [
            static::TYPE_LINK => Links::className(),
            static::TYPE_VIDEO => Videos::className(),
            static::TYPE_FILE => Files::className(),
            static::TYPE_DER => Ders::className(),
            static::TYPE_CONFERENCE => Conferences::className()
        ];

        return new $models[$row['type']]();

    }

    public function attributesToInfo()
    {
        return [];
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'themesBehavior' => [
                'class' => DynamicSelectBehavior::className(),
                'select_attributes' => [
                    'theme' => [
                        'target_attribute' => 'theme_id',
                        'instance' => function($value) {
                            $model = new Themes();
                            $model->name = $value;
                            $model->dic = 'DicQuestionThemes';
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
    public static function tableName()
    {
        return 'materials';
    }

    /**
     * @return ActiveQuery
     */
    public static function find()
    {
        return parent::find()->andOnCondition([
            'OR' , [
                'language' => \Yii::$app->language
            ],
            [
                'language' => null
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language'], 'safe'],
            [['is_shared','access_by_link'], 'safe'],
            [['is_shared','access_by_link'], 'filter', 'filter' => function($value) {
                return $value ? 1 : 0;
            }],
            [['name', 'type'], 'required'],
            [['type', 'user_id'], 'integer'],
            [['tagsString'], 'string'],
            [['description'], 'string', 'max' => 2000],
            [['name', 'source', 'theme'], 'string', 'max' => 255],
            [['theme_id'], 'integer']
        ];
    }

    public function getMtype()
    {
        if ($this->type == self::TYPE_VIDEO) {
            return self::MTYPE_VIDEO;
        }

        if ($this->type == self::TYPE_LINK) {
            return self::MTYPE_LINK;
        }

        if ($this->type == self::TYPE_FILE) {

            $extensions = [
                self::MTYPE_IMAGE => ['jpg', 'png', 'gif', 'jpeg'],
                self::MTYPE_ARCHIVE => ['rar', 'zip', '7z', 'tar', 'jar'],
                self::MTYPE_AUDIO => ['mp3'],
                self::MTYPE_DOCUMENT => ['doc','docx','xls','xlsx', 'pdf'],
                self::MTYPE_TEXT => ['txt','rtf'],
                self::MTYPE_PRESENTATION => ['ppt','pptx','flp'],
                self::MTYPE_HTML => ['html','mhtml','htm']
            ];

            foreach ($extensions as $mtype => $exts) {
                if (in_array($this->infoJson['file']['ext'], $exts)) {
                    return $mtype;
                }
            }

        }

    }

    public function getIcon()
    {

        if ($this->type == self::TYPE_VIDEO) {
            return "video";
        }

        if ($this->type == self::TYPE_LINK) {
            return "link";
        }

        if ($this->type == self::TYPE_DER) {
            return "der";
        }

        if ($this->type == self::TYPE_CONFERENCE) {
            return "conference";
        }

        $exts = [
            'zip' => ['zip','rar','7z','jar','tar'],
            'doc' => ['docx'],
            'flp' => ['flp'],
            'html' => ['html','htm','mhtml']
        ];

        $icon_types = [
            'image/' => "img",
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xls',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'ppt',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'doc',
            'video/' => 'video',
            'text/' => 'txt',
            'audio/' => 'audio',
            'application/pdf' => 'pdf',
            'application/x-rar-compressed' => 'zip',
            'application/zip' => 'zip'
        ];

        $i = 'file';
        foreach ($exts as $k => $ext)
        {
            if (in_array($this->infoJson['file']['ext'],$ext)) {
                return $k;
            }
        }

        foreach ($icon_types as $k=>$t) {
            if ($this->infoJson['file']['type'] == $k OR strpos($this->infoJson['file']['type'], $k)!== false) {
                return $t;
            }
        }

        return $i;

    }

    public function getParsedText()
    {
        $matches = array();
        preg_match_all('/(?P<links>(http:\/\/)?(www\.)?schola\.kz\/mbank\/view\/(?P<ids>\d+))/Dui', $this->description, $matches);
        $text = preg_replace("/((http:\/\/)?(www\.)?schola\.kz\/mbank\/view\/(\d+))/Dui", "material_$4", $this->description);

        if ($matches['ids']) {

            $materials = Materials::find()
                ->notDeleted()
                ->andWhere([
                    "in", "id", $matches['ids']
                ])
                ->indexBy("id");
            $materials = $materials->all();

            foreach ($matches['ids'] as $id) {

                if (is_numeric($id)) {
                    if (isset($materials[$id])) {
                        $text = str_replace("material_".$id, EMaterial::widget([
                            "backbone" => "false",
                            "type" => "link",
                            "model"=>$materials[$id]
                        ]), $text);
                    } else {
                        $text = str_replace("material_".$id, "", $text);
                    }
                }


            }
        }
        return $text;

    }

    public function getShortName()
    {
        $s = $this->name;
        return (mb_strlen($s, "UTF-8")>70 ? mb_substr($s, 0, 67, "UTF-8")."..." : $s);
    }

    public function getShortDescription()
    {
        $s = $this->description;
        return (mb_strlen($s, "UTF-8")>200 ? mb_substr($s, 0, 197, "UTF-8")."..." : $s);
    }

    public function getCanDelete()
    {
        if ($this->user_id == \Yii::$app->user->id OR \Yii::$app->user->can("SUPER")) {
            return true;
        }
        return false;
    }

    public function getCanEdit()
    {
        if ($this->user_id == \Yii::$app->user->id OR \Yii::$app->user->can("SUPER")) {
            return true;
        }
        return false;
    }

    public function getEvents()
    {
        return $this->hasMany(EventMaterial::className(), ['related_id' => 'id']);
    }

    public function getTheme()
    {
        return DicValues::fromDic($this->theme_id);
    }

    public function getMaterialInfoString()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('main', 'ID'),
            'name' => \Yii::t('main', 'Название материала'),
            'theme' => \Yii::t('main', 'Тема'),
            'tagsString' => \Yii::t("main","Ключевые слова"),
            'type' => \Yii::t('main', 'Type'),
            'info' => \Yii::t('main', 'Info'),
            'description' => \Yii::t("main","Краткое описание"),
            'source' => \Yii::t("main","Источник"),
            'ts' => \Yii::t('main', 'Ts'),
            'user_id' => \Yii::t('main', 'User ID'),
            'language' => \Yii::t("main", "Язык материала")
        ];
    }

}


