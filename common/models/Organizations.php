<?php

namespace common\models;

use app\components\VarDumper;
use common\components\ActiveRecord;
use common\models\relations\UserOrganization;
use common\traits\AttributesToInfoTrait;
use common\traits\UpdateInsteadOfDeleteTrait;
use Yii;

/**
 * This is the model class for table "organizations".
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property string $ts
 * @property int $is_deleted
 * @property string $info
 */
class Organizations extends ActiveRecord
{

    use UpdateInsteadOfDeleteTrait, AttributesToInfoTrait;

    const TYPE_GOS_SYSTEM = 1;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organizations';
    }

    public function attributesToInfo()
    {
        return ['logo', 'child_organizations'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['logo'], 'string']
        ];
    }

    public function getUsers()
    {
        return $this->hasMany(UserOrganization::className(), ['target_id' => 'id'])->indexBy("related_id");
    }

    public function getTypeCaption()
    {
        $captions = [
            static::TYPE_GOS_SYSTEM => \Yii::t("main","Гос.")
        ];
        return $captions[$this->type];
    }

    public function getChildOrganizations()
    {
        if ($this->child_organizations) {
            if (!Yii::$app->has("child_organizations")) {

                $organizations = Organizations::find()->indexBy('id');
                if ($this->child_organizations['types']) {
                    $organizations->andWhere([
                            'in', 'organizations.type', $this->child_organizations['types']
                        ]);
                }
                if ($this->child_organizations['ids']) {
                    $organizations->andWhere([
                            'in', 'organizations.id', $this->child_organizations['ids']
                        ]);

                }
                Yii::$app->set("child_organizations", function() use ($organizations) {
                    return $organizations;
                });
            }
            return Yii::$app->get("child_organizations");
        }
        return false;
    }

    public static function setCurrentOrganization($organization = null, $id = null)
    {
        if ($organization) {
            static::$_current_organization = $organization;
        }
        static::$_id = $id ?: ($organization ? $organization->id : null);
    }

    public static $_current_organization = -1;
    public static function getCurrentOrganization()
    {
        if (self::$_current_organization === -1) {
            if (self::getCurrentOrganizationId()) {
                self::$_current_organization = static::find()->byPk(self::getCurrentOrganizationId())->one();
            } else {
                if (!\Yii::$app->user->isGuest AND \Yii::$app->user->identity->organizations) {
                    self::$_current_organization = static::find()->byPk(\Yii::$app->user->identity->organizations[0]->target_id)->one();
                } else {
                    self::$_current_organization = null;
                }

            }
        }
        return self::$_current_organization;
    }

    public static $_id = -1;
    public static function getCurrentOrganizationId()
    {
        if (self::$_id === -1) {
            $id = null;
            if (!\Yii::$app->request->isConsoleRequest) {
                $id = \Yii::$app->request->getHeaders()->get('X-ORGANIZATION-ID') ?: \Yii::$app->request->get('oid');
            } else {
                $id = \Yii::$app->controller->organization_id;
            }
            if (!$id AND \Yii::$app->user->can("SUPER")) {
                $id = 0;
            }
            self::$_id = $id;
        }
        return self::$_id;
    }

}
