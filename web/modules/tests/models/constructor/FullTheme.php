<?php
namespace app\modules\tests\models\constructor;

use app\models\relations\TestTheme;
use common\components\Model;
use common\models\Themes;

class FullTheme extends Base
{

    public $id = null;

    public $weight = 100;
    public $weight_type = 1;

    public function rules()
    {
        return [
            [['test'], 'required'],
            [['theme_id', 'weight', 'weight_type'], 'required'],
            [['weight'], 'integer'],
            [['theme_id'], function() {
                if (TestTheme::find()->byOrganization()->andWhere([
                    'target_id' => $this->test->id,
                    'related_id' => $this->theme_id
                ])->exists()) {
                    $this->addError("theme_id", \Yii::t("main","Данная тема уже прикреплена"));
                    return false;
                }
                return true;
            }, 'when' => function($model) {
                return !$model->id;
            }],
            [['weight_type'], 'integer', 'min' => TestTheme::WEIGHT_TYPE_PERCENT, 'max' => TestTheme::WEIGHT_TYPE_COUNT],
            [['theme_id'], 'integer'],
        ];
    }

    public function fields()
    {
        return ['theme_id','weight','weight_type'];
    }

    public function getThemes()
    {
        return Themes::find()->byOrganization()->with(['questions'])->orderBy('name')->all();
    }

    public function getWeightTypes()
    {
        return [
            TestTheme::WEIGHT_TYPE_PERCENT => \Yii::t("main","Процент"),
            TestTheme::WEIGHT_TYPE_COUNT => \Yii::t("main","Количество"),
        ];
    }

    public function save()
    {

        if (!$this->validate()) return false;

        $th = new TestTheme();
        if ($this->id) {
            $th = TestTheme::find()->byOrganization()->byPk($this->id)->one();
            if (!$th) {
                $this->addError("id", "NO");
                return false;
            }
        }
        $th->target_id = $this->test->id;
        $th->related_id = $this->theme_id;
        $th->weight = $this->weight;
        $th->weight_type = $this->weight_type;

        if (!$th->save()) {
            $this->addErrors($th->getErrors());
            return false;
        }

        return true;

    }

    public function loadAttributes($test_theme)
    {
        $this->id = $test_theme->id;
        $this->attributes = $test_theme->attributes;
    }

}