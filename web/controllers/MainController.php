<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 04.08.2017
 * Time: 16:08
 */

namespace app\controllers;


use app\components\Controller;
use app\components\VarDumper;
use app\helpers\OrganizationUrl;
use app\traits\BackboneRequestTrait;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use common\models\Instructions;
use common\models\Organizations;
use common\models\Support;
use phpbb\request\request;
use yii\filters\AccessControl;
use yii\helpers\Url;

class MainController extends Controller
{

    public function actionIndex()
    {

        if (!\Yii::$app->user->isGuest) {
            \Yii::$app->response->redirect(OrganizationUrl::to(['/cabinet/base/index']));
            return;
        }

        \Yii::$app->response->redirect(Url::to(['/auth/login']));

        return $this->render("index.twig", [

        ]);
    }

    public function actionUpdates()
    {
        return $this->render("updates");
    }

    public function actionSupport()
    {

        $model = new Support();

        if (\Yii::$app->request->post('Support')) {
            $model->attributes = \Yii::$app->request->post('Support');
            if ($model->save()) {
                \Yii::$app->session->addFlash("success", \Yii::t("main", "Ваша заявка успешно принята. Наши специалисты свяжутся с вами в ближайшее время"));
                return $this->renderJSON([]);
            }
            return $this->renderJSON($model->getErrors(), true);
        }

        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, [], [], true);

        return $this->render("support", [
            "model" => $model
        ]);

    }

    public function actionWebinar()
    {

        $recordingParams = new GetRecordingsParameters();
        $response = \Yii::$app->bbb->i()->getRecordings($recordingParams);

        if ($response->getReturnCode() == 'SUCCESS') {
            foreach ($response->getRawXml()->recordings->recording as $recording) {
                VarDumper::dump($recording);
            }
        }

    }

    public function actionDownload()
    {

        $instruction = \Yii::$app->request->get("instruction");

        $data = Instructions::getInstruction($instruction);

        $string = urlencode($data['label']);

        header('Content-type: application/octet-stream');
        header("Content-Disposition: attachment; filename=\"".$string."\"");
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Pragma: public');
        readfile(\Yii::$app->params['host'].$data['url']);
        die();

    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?']
                    ]
                    // everything else is denied by default
                ],
            ],
        ]);
    }


}