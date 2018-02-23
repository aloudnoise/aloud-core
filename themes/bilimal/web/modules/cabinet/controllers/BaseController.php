<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 23.01.2018
 * Time: 12:45
 */

namespace bilimal\web\modules\cabinet\controllers;


use app\components\Controller;
use app\helpers\OrganizationUrl;
use app\helpers\Url;
use common\models\Organizations;

class BaseController extends \app\modules\cabinet\controllers\BaseController
{
    public function actionIndex()
    {

        $role = \Yii::$app->user->identity->system_role ?: \Yii::$app->user->identity->currentOrganizationRole;

        $organization = Organizations::getCurrentOrganization();
        if (!$organization AND !$role == 'SUPER') {
            $organization = \Yii::$app->user->identity->organizationsList[0];
        }

        $redirect = [
            'SUPER' => $organization ? OrganizationUrl::to(['/hr/users/index', 'oid' => $organization->id]) : Url::to(['/cabinet/admin/index']),
            'admin' => OrganizationUrl::to(['/events/index', 'oid' => $organization->id]),
            'specialist' => OrganizationUrl::to(['/events/index', 'oid' => $organization->id]),
            'teacher' => OrganizationUrl::to(['/events/index', 'oid' => $organization->id]),
            'pupil' => OrganizationUrl::to(['/events/index', 'oid' => $organization->id])
        ];

        \Yii::$app->response->redirect($redirect[$role] ?: OrganizationUrl::to(['/events/index', 'oid' => $organization->id]));

    }
}