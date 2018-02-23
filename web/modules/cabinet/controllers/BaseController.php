<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 04.08.2017
 * Time: 16:31
 */

namespace app\modules\cabinet\controllers;

use app\components\Controller;
use app\helpers\OrganizationUrl;
use common\models\Organizations;
use yii\helpers\Url;

class BaseController extends Controller
{

    public function actionIndex()
    {

        if (\Yii::$app->user->identity->active_organization_id) {
            Organizations::setCurrentOrganization(null, \Yii::$app->user->identity->active_organization_id);
        }

        $role = \Yii::$app->user->identity->system_role ?: \Yii::$app->user->identity->currentOrganizationRole;

        $organization = Organizations::getCurrentOrganization();
        if (!$organization AND !$role == 'SUPER') {
            $organization = \Yii::$app->user->identity->organizationsList[0];
        }

        $redirect = [
            'SUPER' => $organization ? OrganizationUrl::to(['/hr/users/index', 'oid' => $organization->id]) : Url::to(['/cabinet/admin/index']),
            'admin' => OrganizationUrl::to(['/hr/users/index', 'oid' => $organization->id]),
            'specialist' => OrganizationUrl::to(['/hr/users/index', 'oid' => $organization->id]),
            'teacher' => OrganizationUrl::to(['/events/index', 'oid' => $organization->id]),
            'pupil' => OrganizationUrl::to(['/events/index', 'oid' => $organization->id])
        ];

        \Yii::$app->response->redirect($redirect[$role] ?: OrganizationUrl::to(['/events/index', 'oid' => $organization->id]));

    }

    public function actionChangeOrganization()
    {

        $organizations = \Yii::$app->user->identity->organizationsList;
        $organization = array_filter($organizations, function($org) {
            return $org->id = \Yii::$app->request->get("organization_id");
        });

        if ($organization) {
            \Yii::$app->user->identity->active_organization_id = $organization[0]->id;
            \Yii::$app->user->identity->save();
            \Yii::$app->response->redirect(Url::to(['/cabinet/base/index', 'oid' => $organization[0]->id]));
        }
    }

}