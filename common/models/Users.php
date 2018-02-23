<?php

namespace common\models;

use app\traits\BackboneRequestTrait;
use common\components\ActiveRecord;
use common\components\JsonValidator;
use common\queries\UsersQuery;
use common\models\relations\UserOrganization;
use common\traits\AttributesToInfoTrait;
use common\traits\DateFormatTrait;
use common\traits\UpdateInsteadOfDeleteTrait;
use common\traits\PhoneTrait;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $ts
 * @property integer $is_registered
 * @property string $fio
 *
 * @property TokenAuthorization[] $tokenAuthorizations
 */
class Users extends ActiveRecord implements IdentityInterface
{
    use UpdateInsteadOfDeleteTrait;
    use BackboneRequestTrait;
    use PhoneTrait;
    use DateFormatTrait, AttributesToInfoTrait;

    public $authorization_token = null;
    public $repassword = null;

    const ROLE_GUEST = 'guest';
    const ROLE_PUPIL = 'pupil';
    const ROLE_TEACHER = 'teacher';
    const ROLE_SPECIALIST = 'specialist';
    const ROLE_ADMIN = 'admin';

    const ROLE_SUPER = 'SUPER';

    public function attributesToInfo()
    {
        return ['active_organization_id'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['profile'] = ['photo', 'fio'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['photo'], JsonValidator::class],
            [['photo'], 'filter', 'filter' => function($value) {
                return is_array($value) ? json_encode($value) : $value;
            }],
            [['fio'], 'string', 'max' => 250],
        ];
    }

    /**
     * @return UsersQuery
     */
    public static function find()
    {
        return new UsersQuery(get_called_class());
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return Users|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return array|ActiveRecord|null|bool the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return;
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    public function getOrganizations()
    {
        return $this->hasMany(UserOrganization::className(), ['related_id' => 'id']);
    }

    private $_list = null;
    public function getOrganizationsList()
    {
        if (!$this->_list) {
            if (\Yii::$app->user->can("SUPER")) {
                if (!$this->_list) {
                    $organizations = Organizations::find()->orderBy("name");
                    $moderation = $this->infoJson['moderation'];
                    if ($moderation AND $moderation['organization_types']) {
                        $organizations->andWhere([
                            'in', 'type', $moderation['organization_types']
                        ]);
                    }
                    $this->_list = $organizations->all();
                }
            } else {
                $orgs = $this->organizations;
                if ($orgs) {
                    foreach ($orgs as $org) {
                        $this->_list[] = $org->organization;
                    }
                } else {
                    $orgs = [];
                }
            }

        }
        return $this->_list;
    }

    public function getTests()
    {
        return $this->hasMany(Tests::className(), ['user_id' => 'id']);
    }

    public function getQuestions()
    {
        return $this->hasMany(Questions::className(), ['user_id' => 'id']);
    }


    public function getEvents()
    {
        return $this->hasMany(Events::className(), ['user_id' => 'id']);
    }

    public function fields()
    {
        return ['id'];
    }

    public function getLogin()
    {
        return $this->credentials['login'] ? $this->credentials['login']->credential : null;
    }

    public function getEmail()
    {
        return $this->credentials['email'] ? $this->credentials['email']->credential : null;
    }

    public function getPhone()
    {
        return $this->credentials['phone'] ? $this->credentials['phone']->credential : null;
    }

    public function getEmailOrPhone()
    {
        return $this->email ?: $this->phone;
    }

    public function getPhotoUrl()
    {
        $photo = $this->photoJson;
        if (!empty($photo['preview'])) {
            return $photo['preview'];
        }
        return \Yii::$app->assetManager->getBundle('base')->baseUrl . '/img/default_ava.png';
    }

    public function getPhotoUrlBig()
    {
        $photo = $this->photoJson;
        if (!empty($photo['url'])) {
            return $photo['url'];
        }
        return \Yii::$app->assetManager->getBundle('base')->baseUrl . '/img/default_ava_0.png';
    }

    public function getFioOrLogin()
    {
        return $this->fio ?: $this->email;
    }

    public function getFioShortOrLogin()
    {
        return $this->fioShort ?: $this->email;
    }

    public function getFioShort()
    {
        $fio = explode(" ", $this->fio);
        if ($fio[1]) {
            $fio[1] = mb_substr($fio[1],0,1).".";
        }
        if ($fio[2]) {
            $fio[2] = mb_substr($fio[2],0,1).".";
        }
        return $fio[0].($fio[1] ? " ".$fio[1] : "").($fio[2] ? " ".$fio[2] : "");
    }

    public function getRoleName()
    {
        return static::getRoles()[$this->getCurrentOrganizationRole()];
    }

    public function getCredentials()
    {
        return $this->hasMany(AuthCredentials::className(), ['user_id' => 'id'])->indexBy("type");
    }

    public function getCanEdit()
    {
        return $this->id == \Yii::$app->user->id OR \Yii::$app->user->can("admin");
    }

    public static function getRoles()
    {
        return [
            self::ROLE_PUPIL => \Yii::t("main","Слушатель"),
            self::ROLE_TEACHER => \Yii::t("main","Учитель"),
            self::ROLE_SPECIALIST => \Yii::t("main","Главный специалист"),
            self::ROLE_ADMIN => \Yii::t("main","Администратор"),
        ];
    }

    public function getCurrentOrganizationRelation()
    {
        $organization = Organizations::getCurrentOrganization();
        if ($organization) {
            $relation = $organization->users[$this->id];
            return $relation;
        }
        return null;
    }

    private $_role = null;
    public function getCurrentOrganizationRole()
    {

        if ($this->system_role === self::ROLE_SUPER) {
            return self::ROLE_SUPER;
        }

        if ($this->_role === null) {
            $organization = Organizations::getCurrentOrganization();
            if ($organization) {
                $role = $organization->users[$this->id];
                if ($role) {
                    $this->_role = $role->role;
                }

            }
            if ($this->_role === null) {
                $this->_role = false;
            }
        }

        return $this->_role;
    }

}
