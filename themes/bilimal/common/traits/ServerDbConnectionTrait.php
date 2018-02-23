<?php
namespace bilimal\common\traits;

use bilimal\web\models\old\ServerList;
use yii\db\Exception;

/**
 * Делает подключение к базе относительно переданного параметра в заголовке X-SERVER-ID или $_GET['sid']
 * Используйте данный трейт в моделях, которые должны подключаться сразу к СЕРВЕРУ
 * Class ServerDbConnectionTrait
 * @package app\components
 */
trait ServerDbConnectionTrait
{
    /**
     * @return \yii\db\Connection
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDb()
    {
        if (ServerList::getServerId()) {
            $sid = ServerList::getServerId();
            $school_db = 'school_db' . $sid;
            if(!isset(\Yii::$app->{$school_db}))
            {
                $server = ServerList::findOne($sid);
                $component = \Yii::createObject([
                    'class' => 'common\components\Connection',
                    'dsn' => 'pgsql:host='.($server['domain']).';dbname='.($server['db_name']),
                    'username' => $server['user'],
                    'password' => $server['password'],
                    'charset' => 'utf8',
                    'enableSchemaCache' => true,
                    'enableQueryCache' => true,
                    'schemaCacheDuration' => 3600,
                    'schemaCache' => 'cache',
                ]);
                \Yii::$app->set($school_db,$component);
            }
            return \Yii::$app->{$school_db};

        }
        if (!method_exists("parent", "getDb")) {
            throw new Exception("NO GET DB METHOD IN PARENT");
        }
        return parent::getDb();
    }

    public static function getServerDbConnection($server)
    {
        $school_db = 'school_db' . $server->id;
        if(!isset(\Yii::$app->{$school_db}))
        {
            $component = \Yii::createObject([
                'class' => 'common\components\Connection',
                'dsn' => 'pgsql:host='.($server['domain']).';dbname='.($server['db_name']),
                'username' => $server['user'],
                'password' => $server['password'],
                'charset' => 'utf8',
                'enableSchemaCache' => true,
                'enableQueryCache' => true,
                'schemaCacheDuration' => 3600,
                'schemaCache' => 'cache',
            ]);
            \Yii::$app->set($school_db,$component);
        }
        return \Yii::$app->{$school_db};
    }

}

?>