<?php
namespace app\controllers;

use app\components\Controller;
use app\components\VarDumper;
use app\models\filters\OrganizationsFilter;
use common\models\From;
use common\models\Organizations;
use yii\data\ActiveDataProvider;

class OrganizationsController extends Controller
{

    public function actionAssign()
    {

        $filter = \Yii::createObject(OrganizationsFilter::className());
        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $query = (Organizations::getCurrentOrganization())->getChildOrganizations();

        if (\Yii::$app->request->get("from")) {
            $relation = From::instance()->getAssignedRelationClass('organization');
            $filter->exclude_ids = $relation::find()->select(['related_id'])->andWhere([
                'target_id' => From::instance()->id
            ])->asArray()->column();
        }

        $filter->applyFilter($query);

        $query->orderBy([
            'organizations.name' => SORT_ASC,
        ]);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100
            ]
        ]);

        return $this->render("assign", [
            "provider" => $provider,
            "filter" => $filter,
        ]);
    }

}