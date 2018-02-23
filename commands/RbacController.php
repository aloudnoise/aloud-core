<?php
namespace commands;

use yii;

class RbacController extends yii\console\Controller
{
    public function actionInit()
    {
        $authManager = \Yii::$app->authManager;
        $authManager->removeAll();

        $guest  = $authManager->createRole('guest');

        $base_teacher = $authManager->createRole("base_teacher");
        $teacher = $authManager->createRole("teacher");

        $base_pupil = $authManager->createRole("base_pupil");
        $pupil = $authManager->createRole("pupil");

        $specialist = $authManager->createRole("specialist");

        $admin = $authManager->createRole("admin");

        $super = $authManager->createRole('SUPER');

        $authManager->add($guest);
        $authManager->add($super);
        $authManager->add($base_teacher);
        $authManager->add($specialist);
        $authManager->add($admin);
        $authManager->add($teacher);
        $authManager->add($base_pupil);
        $authManager->add($pupil);

        $authManager->addChild($pupil, $base_pupil);
        $authManager->addChild($base_teacher, $pupil);
        $authManager->addChild($teacher, $base_teacher);
        $authManager->addChild($specialist, $teacher);
        $authManager->addChild($admin, $specialist);
        $authManager->addChild($super, $admin);

    }

}
?>