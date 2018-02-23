<?php

namespace common\models;
use common\components\ActiveRecord;
use common\traits\UpdateInsteadOfDeleteTrait;

/**
 * This is the model class for table "dics".
 *
 * The followings are the available columns in table 'dics':
 * @property integer $id
 * @property string $dic
 * @property string $name
 * @property string $business_type
 * @property integer $business_id
 * @property integer $ts
 * @property string $info
 */
class DicValues extends ActiveRecord
{

    use UpdateInsteadOfDeleteTrait;

    public $parent_category = null;

    private static $_values = null;
    private static $_byCat = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dic_values';
    }

    private static function _populate()
    {
        if (!self::$_values) {
            self::$_values = self::find()
                ->with(['dicModel' => function($q) {
                    return $q->byOrganizationOrNull()->byOrganizationTypeOrNull();
                }])
                ->indexBy('id')
                ->byOrganizationOrNull()
                ->byOrganizationTypeOrNull()
                ->all();

            foreach (self::$_values as $value) {

                if (!isset(self::$_byCat[$value->dicModel->name])) {
                    self::$_byCat[$value->dicModel->name] = [];
                }

                self::$_byCat[$value->dicModel->name][$value->id] = $value;
            }
        }
    }

    public static function fromDic($value, $inputs = null)
    {
        self::_populate();
        return isset(self::$_values[$value]) ? self::$_values[$value]->getNameWithInputs($inputs) : (!empty($value) ? "Неизв." : "");
    }

    public static function fromDicModel($value) {
        self::_populate();
        return isset(self::$_values[$value]) ? self::$_values[$value] : new DicValues();
    }

    public static function findByDic($name) {
        self::_populate();
        return self::$_byCat[$name];
    }

    public function getDicModel()
    {
        return $this->hasOne(Dics::className(), ['name' => 'dic']);
    }

    public function getNameWithInputs($inputs) {
        $n = explode("{input}", $this->nameByLang);
        if (count($n) > 1) {
            $nn = "";
            for ($i=0; $i<count($n); $i++) {
                $nn .= $n[$i];
                if ($i != count($n)-1) {
                    if (is_array($inputs)) {
                        $nn .= $inputs[$i][0];
                    } else {
                        $nn .= $inputs;
                    }
                }
            }
            return $nn;
        }
        return $this->nameByLang;

    }

    public function getNameOnForm()
    {
        $n = explode("{input}", $this->name);
        if (count($n) > 1) {
            $nn = "";
            for ($i=0; $i<count($n); $i++) {
                $nn .= $n[$i];
                if ($i != count($n)-1) {
                    $nn .= "<input style='width:80px;' dname='".$this->dic->name."' dvalue='".$this->id."' i='".$i."' name='dic_inputs[".$this->dic->name."][".$this->id."][$i][]' class='form-control input-xs inline-block' />";
                }
            }
            return $nn;
        }
        return $this->name;
    }

    public function getAdValue()
    {
        return $this->infoJson['advalue'];
    }

    public function setAdValue($val)
    {
        $this->setInfo("advalue", $val);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max'=>'255'],
            [['name', 'adValue'], 'safe'],
            [['parent_id'], 'integer'],

        ];
    }
}
