<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 15.11.2017
 * Time: 14:03
 */

namespace app\controllers;


use app\components\Controller;
use app\components\VarDumper;
use app\helpers\OrganizationUrl;
use app\models\Example;
use app\models\Materials;
use app\traits\BackboneRequestTrait;
use common\models\Organizations;
use yii\data\ActiveDataProvider;

// Пример базовых вещей
class ExampleController extends Controller
{

    public function actionIndex()
    {

        VarDumper::dump(\Yii::$classMap);

        $model = new Example();
        $model->one_attr = 'asd';
        $model->two_attr = 'hfd';
        $model->three_attr = 123;

        $filter = new Example();
        //$filter->name = 'x'; Можно применить разную фильтрацию

        $query = Example::find();

        // Каждая организация в системе разделяется во всех таблицах по organization_id
        // Если залогиненный пользователь принадлежит к организации, то его перенаправляет по правилу
        // /{organization_id}/route/to/action
        // чтобы получить айдишник текущей организации
        $id = Organizations::getCurrentOrganizationId();
        $org = Organizations::getCurrentOrganization();
        // Чтобы составить урл по текущей организации
        OrganizationUrl::to(['/example/index']); // автоматом подставить айдишник в начало урла

        // Чтобы отфильровать запрос чисто по организации
        $query->byOrganization();

        $filter->applyFilter($query);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30
            ]
        ]);

        // Чтобы передать в бекбон любые переменные
        \Yii::$app->data->x = ['1','2','3'];
        // Чтобы передать модель исполюзуем метод arrayAttributes, смотри в BackboneRequestTrait;
        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, [], ['one_attr','two_attr','three_attr'], true);


        // Контролллер на бэкбоне смотри в /protected/assets/backbone/controllers
        // Все js логику пихать исключительно туда, никакого жса во вьюхах и отдельных js файлах

        // Рендерить можно как twig шаблоны, так и просто php файлы
        // Но лучше в дальнейшем использовать только twig, пхп остались от старой системы, потом все переведем на twig
        // Доп функции в twig можно дописывать в web.php в конфиге (components -> view)
        return $this->render("index.twig", [
            "provider" => $provider
        ]);

    }

    public static $modelClass = 'app\models\Example';
    // Пример добавления/редактирования записи
    public function actionAdd()
    {

        // Получает модель из гет id либо создает новый экземпляр
        $model = self::getModel(null, false);

        // Если приходят данные с формы
        if (\Yii::$app->request->post("Example")) {
            $model->attributes = \Yii::$app->request->post("Example");
            if ($model->save()) {
                // Если запись сохранилась то отправляем сообщение об успешном добавлении и указываем в ответе на форму, редирект
                \Yii::$app->session->setFlash("success", \Yii::t("main","Запись успешно добавлена"));
                return $this->renderJSON([
                    'redirect' => OrganizationUrl::to(['/example/index'])
                ]);
            }
            // Если валидация не прошла, отправляем обратно на форму ошибки модели
            return $this->renderJSON($model->getErrors(), true);
        }

        // Передаем модель в форму на бэкбоне
        // третим параметром надо передать список аттрибутов модели которые уйдут в жс,
        // если передать пустой массив, то отправятся все встроенные аттрибуты, если нужно передать кастомные, их надо прописать вручную
        \Yii::$app->data->model = BackboneRequestTrait::arrayAttributes($model, [], array_merge((new Example())->attributes(), ['one_attr','two_attr','three_attr']), true);

        return $this->render("form.twig", [
            "model" => $model
        ]);

    }

}