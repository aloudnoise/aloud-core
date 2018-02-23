<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 19.02.2018
 * Time: 14:46
 */

namespace bilimal\web\controllers;


use bilimal\web\models\BilimalOrganization;
use bilimal\web\models\filters\ServersFilter;
use bilimal\web\models\Organizations;
use common\models\From;
use yii\data\ActiveDataProvider;

class OrganizationsController extends \app\controllers\OrganizationsController
{

    public function actionAssign()
    {
        $filter = \Yii::createObject(ServersFilter::className());
        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $query = (Organizations::getCurrentOrganization())->getChildServers();

        if (\Yii::$app->request->get("from")) {
            $relation = From::instance()->getAssignedRelationClass('organization');
            $target_ids = $relation::find()->select(['related_id'])->andWhere([
                'target_id' => From::instance()->id
            ])->asArray()->column();

            $filter->exclude_ids = BilimalOrganization::find()->select(['institution_id'])->andWhere([
                'in', 'organization_id', $target_ids
            ])->asArray()->column();

        }

        $filter->applyFilter($query);

        $query->orderBy([
            'server_list.caption' => SORT_ASC,
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