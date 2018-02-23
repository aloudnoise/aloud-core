<?php
namespace bilimal\web\models\forms;
use bilimal\web\models\LinksGenerator;
use bilimal\web\models\old\Users;
use bilimal\web\models\version2\PersonCredential;

/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 04.12.2017
 * Time: 14:34
 */
class LoginForm extends \app\models\forms\LoginForm
{

    public function validateAndLogin()
    {
        if (!$r = parent::validateAndLogin()) {
            return static::validateAndLoginFromBilimal();
        }
        return $r;
    }

    public function validateAndLoginFromBilimal()
    {


        if ($this->validate()) {

//            $identity = PersonCredential::find()->andWhere('lower(indentity) = :login', [
//                ':login' => trim($this->login)
//            ])->one();
//
//            // TODO KOSTIL' VHOD BEZ PAROLYA
//            if ($identity) {
//
//                // Проверяем на наличие юзера , организации, связей и тд
//                $links = new LinksGenerator();
//                if (!$links->generateLinks($identity->person))
//                {
//
//                    $this->addErrors($links->getErrors());
//                    return false;
//                }
//
//                \Yii::$app->user->login($links->user, $this->remember_me ? self::MONTH : 0);
//                return  true;
//
//            }

            // TODO - ESLI NETU V NOVOM BILIMALE, SMORIM V STAROM
            $identity = Users::find()->andWhere('lower(login) = :login', [
                ':login' => trim($this->login)
            ])->one();

            if ($identity) {

                // Проверяем на наличие юзера , организации, связей и тд
                $links = new LinksGenerator();

                if (!$links->generateLinksOld($identity))
                {

                    $this->addErrors($links->getErrors());
                    return false;
                }

                \Yii::$app->user->login($links->user, $this->remember_me ? self::MONTH : 0);
                return  true;

            }


        }
        $this->addError("password", \Yii::t("main","Неверный логин или пароль"));
        return false;

    }

}