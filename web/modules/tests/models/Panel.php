<?php
namespace app\modules\tests\models;

use app\modules\tests\models\constructor\FileImport;
use app\modules\tests\models\constructor\FromQuestionsList;
use app\modules\tests\models\constructor\FullTheme;
use app\modules\tests\models\constructor\SimpleQuestion;
use app\modules\tests\models\constructor\TextImport;
use common\components\Model;

class Panel extends Model
{
    public $instrument = null;

    public function getInstruments()
    {
        $instruments = [
            'simple_question' => [
                'icon' => 'plus',
                'label' => \Yii::t("main","Новый вопрос"),
                'description' => \Yii::t("main",'Добавить новый вопрос, с одним или несколькими правильными ответами'),
                'model' => SimpleQuestion::className(),
            ],
            'from_questions_list' => [
                'icon' => 'list',
                'label' => \Yii::t("main","Выбрать из списка"),
                'description' => \Yii::t("main",'Выбрать вопросы из банка вопросов, созданных вами или другими преподавателями'),
                'model' => FromQuestionsList::className()
            ],
            'text_import' => [
                'icon' => 'font',
                'label' => \Yii::t("main","Импорт текстом"),
                'description' => \Yii::t("main",'Вставить текст со списком вопросов из Microsoft Word или другой программы'),
                'model' => TextImport::className()
            ],
            'file_import' => [
                'icon' => 'file',
                'label' => \Yii::t("main","Импорт файлом"),
                'description' => \Yii::t("main",'Загрузить список вопросов из файла Microsoft Excel'),
                'model' => FileImport::className()
            ],
            'full_theme' => [
                'icon' => 'cloud',
                'label' => \Yii::t("main","Вставить тему целиком"),
                'description' => \Yii::t("main",'Встатвить тему со всеми вопросами целиком'),
                'model' => FullTheme::className()
            ]
        ];

        return $instruments;

    }

    public function getInstrumentModel()
    {
        $instruments = $this->getInstruments();
        return $instruments[$this->instrument] ? $instruments[$this->instrument]['model'] : null;
    }

    public function rules()
    {
        return [
            [['instrument'], 'in', 'range' => array_keys($this->instruments)]
        ];
    }

}