<?php
namespace app\components;

use app\models\Users;
use common\models\Organizations;
use yii\base\ActionFilter;
use yii\helpers\Url;

class OrganizationFilter extends ActionFilter
{



    public function beforeAction($action)
    {
        if (!Organizations::getCurrentOrganizationId() AND !\Yii::$app->user->can(Users::ROLE_SUPER))
        {
            $organizations = \Yii::$app->user->identity->organizations;
            if ($organizations) {
                return \Yii::$app->response->redirect(Url::to(array_merge(['/'.\Yii::$app->controller->route], \Yii::$app->request->get(), ['oid' => $organizations[0]->target_id])));
            }
        }
        return parent::beforeAction($action);
    }

}