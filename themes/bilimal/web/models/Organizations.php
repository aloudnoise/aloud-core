<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 04.12.2017
 * Time: 15:36
 */

namespace bilimal\web\models;


use bilimal\web\models\old\CollegeInfo;
use bilimal\web\models\old\ServerList;

class Organizations extends \common\models\Organizations
{

    const TYPE_BILIMAL_SCHOOL_SYSTEM = 5;
    const TYPE_BILIMAL_COLLEGE_SYSTEM = 6;
    const TYPE_BILIMAL_GOROO_SYSTEM = 7;
    const TYPE_BILIMAL_UO_SYSTEM = 8;
    const TYPE_BILIMAL_OTHER_SYSTEM = 9;

    public function getTypeCaption()
    {
        $captions = [
            static::TYPE_BILIMAL_SCHOOL_SYSTEM => \Yii::t("main","Bilimal. Школа"),
            static::TYPE_BILIMAL_COLLEGE_SYSTEM => \Yii::t("main","Bilimal. Колледж"),
            static::TYPE_BILIMAL_GOROO_SYSTEM => \Yii::t("main","Bilimal. Гороо"),
            static::TYPE_BILIMAL_UO_SYSTEM => \Yii::t("main","Bilimal. УО"),
            static::TYPE_BILIMAL_OTHER_SYSTEM => \Yii::t("main","Bilimal. Прочее"),
        ];
        return $captions[$this->type];
    }

    public function getBilimalOrganization()
    {
        return $this->hasOne(BilimalOrganization::className(), ['organization_id' => 'id']);
    }

    public function getChildServers()
    {
        if ($this->child_organizations) {
            if (!\Yii::$app->has("child_servers")) {

                $servers = ServerList::find()->indexBy('id');

                if ($this->child_organizations['types']) {

                    $type_match = [
                        static::TYPE_BILIMAL_SCHOOL_SYSTEM => 1,
                        static::TYPE_BILIMAL_COLLEGE_SYSTEM => 2,
                        static::TYPE_BILIMAL_GOROO_SYSTEM => 5,
                        static::TYPE_BILIMAL_UO_SYSTEM => 6
                    ];

                    $server_types = [];
                    foreach ($this->child_organizations['types'] as $type) {
                        if ($type_match[$type]) {
                            $server_types[] = $type_match[$type];
                        }
                    }

                    $servers->andWhere([
                        'in', 'server_list.type', $server_types
                    ]);
                }

                \Yii::$app->set("child_servers", function() use ($servers) {
                    return $servers;
                });
            }
            return \Yii::$app->get("child_servers");
        }
        return false;
    }

    public static function createOrganizationFromServer($sid, $transaction = null, $model = null)
    {

        ServerList::$_serverId = $sid;

        $serverInfo = CollegeInfo::find()->one();

        $io = BilimalOrganization::find()
            ->andWhere([
                "institution_id" => ServerList::getServerId(),
                'institute_type' => BilimalOrganization::TYPE_OLD_BILIMAL
            ])->one();

        if (!$io) {

            $name = json_decode(str_replace("}","]",str_replace("{","[", $serverInfo->school_name)), true);

            $server = ServerList::find()->byPk(ServerList::getServerId())->one();

            $types = [
                1 => Organizations::TYPE_BILIMAL_SCHOOL_SYSTEM,
                2 => Organizations::TYPE_BILIMAL_COLLEGE_SYSTEM,
                5 => Organizations::TYPE_BILIMAL_GOROO_SYSTEM,
                6 => Organizations::TYPE_BILIMAL_UO_SYSTEM
            ];

            $organization = new Organizations();
            $organization->name = $name ? $name[0] : $serverInfo->school_name;
            $organization->type = $types[$server->type] ?: Organizations::TYPE_BILIMAL_OTHER_SYSTEM;

            if (!$organization->save()) {
                if ($transaction) $transaction->rollBack();
                if ($model) $model->addErrors($organization->getErrors());
                return false;
            }

            $io = new BilimalOrganization();
            $io->institution_id = ServerList::getServerId();
            $io->organization_id = $organization->id;
            $io->institute_type = BilimalOrganization::TYPE_OLD_BILIMAL;

            if (!$io->save()) {
                if ($transaction) $transaction->rollBack();
                if ($model) $model->addErrors($io->getErrors());
                return false;
            }

        } else {
            $organization = Organizations::find()->byPk($io->organization_id)->one();
        }

        return $organization;

    }

}