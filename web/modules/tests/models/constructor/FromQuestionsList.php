<?php
namespace app\modules\tests\models\constructor;

use app\models\forms\QuestionsFilterForm;
use app\models\Questions;
use common\components\Model;
use yii\data\ActiveDataProvider;

class FromQuestionsList extends Base
{
    public $ids = [];

    public function rules()
    {
        return [
            [['ids'], 'each', 'rule' => 'integer']
        ];
    }

    public function getProvider($exclude_ids = [])
    {

        $filter = new QuestionsFilterForm();
        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $questions = Questions::find()->byOrganization()->with([
            "answers",
            "theme"
        ]);

        $filter->applyFilter($questions);

        $questions->orderBy("ts DESC");

        if (!empty($exclude_ids)) {
            $questions->andWhere([
                'not in', 'id', $exclude_ids
            ]);
        }

        $provider = new ActiveDataProvider([
            'query' => $questions,
            'pagination' => [
                'pageSize' => 30
            ]
        ]);

        return $provider;

    }

}