<?php

namespace app\controllers;

use app\components\Controller;

use app\components\VarDumper;
use app\helpers\ArrayHelper;
use app\helpers\bigbluebutton\CreateMeetingParameters;
use app\helpers\OrganizationUrl;
use app\helpers\Url;
use app\models\Events;
use app\models\forms\MaterialsFilterForm;
use app\models\Materials;
use app\models\relations\EventCourse;
use app\models\relations\EventMaterial;
use app\models\results\MaterialResults;
use app\models\results\TaskResults;
use app\models\Users;
use app\traits\BackboneRequestTrait;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use common\models\notifications\ConferenceNotification;
use common\models\Organizations;
use common\models\redis\OnlineUsers;
use common\models\Tags;
use yii\data\ActiveDataProvider;
use yii\base\Exception;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\NotAcceptableHttpException;

class LibraryController extends Controller
{

    public function beforeAction($action) {
        $p = parent::beforeAction($action);

        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Главная"), OrganizationUrl::to(["/main/index"]));
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Библиотека"), OrganizationUrl::to(["/library/index"]));
        return $p;
    }

    public function actionIndex()
    {

        $filter = new MaterialsFilterForm();

        if (!\Yii::$app->user->can("base_teacher")) {
            $filter->pr = 2;
        }

        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $materials = Materials::find()->andWhere([
            'OR',
            [
                'language' => \Yii::$app->language
            ],
            [
                'language' => null
            ]
        ])->byOrganization()->notDeleted();
        $filter->applyFilter($materials);

        $materials->with([
            "views", "downloads", "tags"
        ]);

        $materials->orderBy("ts DESC");

        $provider = new ActiveDataProvider([
            'query' => $materials,
            'pagination' => [
                'pageSize' => 30
            ]
        ]);

        \Yii::$app->data->filter = BackboneRequestTrait::arrayAttributes($filter, [], ['search'], true);

        $tags = Tags::find()->distinct()->byOrganization()->select(['name'])->andWhere([
            'table' => 'materials'
        ])->asArray()->column();

        return $this->render("index", [
            "filter" => $filter,
            "provider" => $provider,
            "tags" => $tags
        ]);

    }

    public function actionAssigned()
    {
        $events = Events::getCurrentEvents();

        $ids = ArrayHelper::map($events, 'id', 'id');

        $materials = EventMaterial::find()
            ->byOrganization()
            ->with([
                'material'
            ])
            ->andWhere([
                'in', 'target_id', $ids
            ])
            ->all();

        $courses = EventCourse::find()
            ->byOrganization()
            ->joinWith([
                'course' => function($q) {
                    return $q->joinWith([
                        'tags',
                        'lessons.materials.material'
                    ]);
                }
            ])
            ->andWhere([
                'IS NOT', 'lesson_material.id', null
            ])
            ->andWhere([
                'in', 'event_course.target_id', $ids
            ])
            ->all();

        return $this->render("assigned", [
            'events' => $events,
            'courses' => $courses,
            'materials' => $materials
        ]);
    }

    public function actionAdd()
    {

        if (\Yii::$app->request->get("type")) {
            $model = Materials::instantiate([
                'type' => \Yii::$app->request->get("type")
            ]);
            $model->type = \Yii::$app->request->get("type");
        } else {
            $model = new Materials();
        }

        if (\Yii::$app->request->get('id')) {
            $model = Materials::find()->byPk(\Yii::$app->request->get('id'))->one();
        }

        if (\Yii::$app->request->post('Materials')) {

            $model->attributes = \Yii::$app->request->post('Materials');

            if ($model->save())
            {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Материал успешно добавлен/обновлен"));
                return $this->renderJSON([
                    "redirect"=> OrganizationUrl::to(["/library/index"])
                ]);
            } else {
                return $this->renderJSON($model->getErrors(), true);
            }

        }

        \Yii::$app->data->tags = Tags::find()->distinct()->byOrganization()->select(['name'])->andWhere([
            'table' => 'materials'
        ])->asArray()->column();
        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, [],  array_merge((new Materials())->attributes(), ['theme','infoJson','tagsString'], $model->attributesToInfo()), true);

        return $this->render("form", [
            "model" => $model
        ]);

    }

    public function actionView($id = null)
    {
        $model = Materials::findWithDeleted()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new Exception("NO MATERIAL PROVIDED");

        $model->addView();

        /* @var $practice_result_query \common\components\ActiveQuery */

        if (\Yii::$app->user->identity->currentOrganizationRole == "pupil") {

            if (\Yii::$app->request->get("from")) {
                $result = new MaterialResults();
                $result->user_id = \Yii::$app->user->id;
                $result->material_id = $model->id;
                $result->process = new Expression("NOW()");
                $result->action = 'view';
                $result->save();
                \Yii::$app->data->result = $result->toArray();
            }

        }
        if ($model->type == Materials::TYPE_DER) {
            \Yii::$app->data->size = "llg";
        }

        \Yii::$app->data->material = BackboneRequestTrait::arrayAttributes($model, [], array_merge((new Materials())->attributes(), ['infoJson', 'tagsString']), true);
        return $this->render("view", [
            "material" => $model
        ]);
    }

    public function actionShared()
    {

        $this->layout = 'blank';

        $model = Materials::find()->byPk(\Yii::$app->request->get("m"), true)->one();
        if (!$model OR !$model->access_by_link) throw new NotAcceptableHttpException("NO");



        return $this->render("shared", [
            'material' => $model
        ]);

    }

    public function actionRestore()
    {
        $model = Materials::findWithDeleted()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new Exception("NO MATERIAL PROVIDED");

        if ($model AND $model->canDelete) {
            $model->is_deleted = 0;
            if ($model->save(false)) {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Материал востановлен"));
                \Yii::$app->response->redirect(OrganizationUrl::to(["/library/view", "id"=>$model->id]));
            } else {
                \Yii::$app->session->setFlash("error",\Yii::t("main","Ошибка востановления"));
                \Yii::$app->response->redirect(OrganizationUrl::to(["/library/index"]));
            }
        } else {
            \Yii::$app->response->redirect(OrganizationUrl::to(["/library/index"]));
        }
    }

    public function actionDelete()
    {
        $model = Materials::find()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new Exception("NO MATERIAL PROVIDED");

        if ($model AND $model->canDelete) {
            if ($model->delete()) {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Материал удален"));
                \Yii::$app->response->redirect(OrganizationUrl::to(["/library/view", "id"=>$model->id]));
            } else {
                var_dump($model->getErrors());
                \Yii::$app->session->setFlash("error",\Yii::t("main","Ошибка удаления"));
                \Yii::$app->response->redirect(OrganizationUrl::to(["/library/index"]));
            }
        } else {
            \Yii::$app->response->redirect(OrganizationUrl::to(["/library/index"]));
        }
    }

    public function actionDownload()
    {

        $model = Materials::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if (!$model) throw new Exception("NO TEST PROVIDED");

        $model->addDownload();

        $string = urlencode($model->infoJson['file']['name']);

        header('Content-type: application/octet-stream');
        header("Content-Disposition: attachment; filename=\"".$string."\"");
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Pragma: public');
        readfile($model->infoJson['file']['url']);
    }

    public function actionBeginConference()
    {
        $model = Materials::find()->byOrganization()->byPk(\Yii::$app->request->get("id"))->one();
        if ($model AND $model->canEdit) {

            if (!$model->moderator_pwd) {
                $model->moderator_pwd = \Yii::$app->security->generateRandomString();
            }
            if (!$model->attendee_pwd) {
                $model->attendee_pwd = \Yii::$app->security->generateRandomString();
            }

            $createMeetingParams = new CreateMeetingParameters($model->meetingId, $model->name);
            $createMeetingParams->setAttendeePassword($model->attendee_pwd);
            $createMeetingParams->setModeratorPassword($model->moderator_pwd);

            $createMeetingParams->setWelcomeMessage($model->welcome ?: "<p>".\Yii::t("main","Добро пожаловать на вебинар \"{name}\"", [
                    'name' => $model->name
                ])."</p>");

            if ($model->presentation) {

                $createMeetingParams->addPresentation($model->presentation['url']);
            }

            $createMeetingParams->setLogoutUrl(OrganizationUrl::to(['/library/exit', 'm' => $model->hash, 'return' => 0, 'from' => 0], true));

            $createMeetingParams->setRecord(true);
            $createMeetingParams->setAllowStartStopRecording(true);
            $createMeetingParams->setAutoStartRecording(true);

            $meeting = \Yii::$app->bbb->createMeeting($createMeetingParams);
            if ($meeting->getReturnCode() == 'FAILED') {
                throw new \yii\db\Exception("CANT CREATE MEETING");
            } else {


                $notification = new ConferenceNotification();
                $notification->organization_id = Organizations::getCurrentOrganizationId();
                $notification->conference_id = $model->id;
                $notification->action = ConferenceNotification::ACTION_BEGUN;
                $notification->send();

                $model->is_live = 1;
                $model->is_over = 0;
                $model->save();

                //$hook = \Yii::$app->bbb->createHook();

                $joinMeetingParams = new JoinMeetingParameters($model->meetingId, \Yii::$app->user->identity->fio, $model->moderator_pwd);
                $joinMeetingParams->setRedirect(true);
                \Yii::$app->response->redirect(\Yii::$app->bbb->getJoinMeetingURL($joinMeetingParams));

            }

        }
    }

    public function actionExit()
    {

        $model = Materials::find()->byOrganization()->byPk(\Yii::$app->request->get("m"), true)->one();
        if ($model AND $model->canEdit) {

//            $endMeetingParams = new EndMeetingParameters($model->meetingId, $model->moderator_pwd);
//            /** @var $i \BigBlueButton\BigBlueButton */
//            $i = \Yii::$app->bbb;
//            $i->endMeeting($endMeetingParams);
//
//            $model->is_over = 1;
//            $model->is_live = 0;
//            $model->over_ts = (new \DateTime())->format('d.m.Y H:i:s');
//
//            $model->save();

            \Yii::$app->response->redirect(OrganizationUrl::to(['/library/view', 'id' => $model->id]));

        } else {

            if ($model->access_by_link) {
                \Yii::$app->response->redirect(Url::to(['/library/shared', 'm' => $model->hash]));
            } else {
                \Yii::$app->response->redirect(OrganizationUrl::to(['/cabinet/base/index']));
            }

        }
    }

    public function actionJoinConference()
    {
        $model = Materials::find()->byPk(\Yii::$app->request->get("m"), true)->one();
        if ($model) {
            if (!\Yii::$app->user->isGuest OR (\Yii::$app->user->isGuest AND $model->access_by_link)) {
                $fio = \Yii::$app->user->isGuest ? (\Yii::$app->request->get("fio") ?: 'Анонимный') : \Yii::$app->user->identity->fio;
                $joinMeetingParams = new JoinMeetingParameters($model->meetingId, $fio, $model->attendee_pwd);
                $joinMeetingParams->setRedirect(true);
                \Yii::$app->response->redirect(\Yii::$app->bbb->getJoinMeetingURL($joinMeetingParams));
            }
        }
    }

    public function actionAutocomplete()
    {
        return $this->renderJSON(Tags::autoComplete("materials", \Yii::$app->request->get('attribute'), \Yii::$app->request->get('query')));
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?','@'],
                        'actions' => ['shared','join-conference', 'exit']
                    ],
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['index', 'assigned', 'view', 'preview', 'download', 'join-conference', 'exit']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['base_teacher'],
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

?>