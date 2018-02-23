<?php

namespace common\components;

use app\helpers\OrganizationUrl;
use TinCan\RemoteLRS;
use TinCan\Statement;
use yii\base\Component;

/**
 * Class TinCanVendor
 * @package glob\components
 *
 * @property \Tincan\RemoteLRS $lrs;
 *
 */
class TinCanVendor extends Component
{

    public $endpoint = null;
    public $version = null;
    public $username = null;
    public $password = null;

    public $lrs = null;

    public $error = "";
    public function init()
    {
        $this->lrs  = new RemoteLRS(
            $this->endpoint,
            $this->version,
            $this->username,
            $this->password
        );

        parent::init();

    }

    public function send($statement)
    {

        $statement = new Statement($statement);

        $response = $this->lrs->saveStatement($statement);
        if ($response->success) {
            return true;
        }
        else {
            $this->error = $response->content;
        }

        return false;

    }

    public function get($query) {

        if (isset($query['since'])) {
            $d = new \DateTime();
            $d->setTimestamp($query['since']);
            $query['since'] = $d->format(DATE_ATOM);
        }

        if (isset($query['until'])) {
            $d = new \DateTime();
            $d->setTimestamp($query['until']);
            $query['until'] = $d->format(DATE_ATOM);
        }

        $response = $this->lrs->queryStatements($query);
        if ($response->success) {
            return $response;
        }
        else {
            $this->error = $response->content;
        }
        return false;
    }

    public function getUrlCredentials()
    {
        $url = "?endpoint=".urlencode($this->endpoint)."&auth=".urlencode("Basic ".base64_encode($this->username.":".$this->password));
        $url.= "&actor=".urlencode(json_encode([
                "name" => \Yii::$app->user->identity->fio,
                "mbox" => "mailto:".\Yii::$app->user->identity->email ? \Yii::$app->user->identity->email : \Yii::$app->user->identity->login."@krw.bilimal.kz",
                "account" => [
                    "id" => \Yii::$app->user->identity->id,
                    "name" => \Yii::$app->user->identity->login ?: \Yii::$app->user->identity->email,
                    "homePage" => \Yii::$app->params['host'].OrganizationUrl::to(['/main/index', 'profile_id' => \Yii::$app->user->identity->id])
                ]
            ]));
        return $url;
    }

}

?>