<?php

namespace aloud_core\common\components;

use aloud_core\common\traits\AccessTrait;
use aloud_core\common\traits\DateFormatTrait;
use yii;

/**
 * Базовая модель.
 * Class ActiveRecord
 *
 * @property bool $isInBusiness
 *
 */
class ActiveRecord extends yii\db\ActiveRecord implements Filterable
{

    use DateFormatTrait, AccessTrait;

    public $search = "";

    const FILTER_SCENARIO = 'filter';
    const FILTER_ONE_SCENARIO = 'filter_one';

    const SCENARIO_INSERT = 'insert';
    const SCENARIO_UPDATE = 'update';

    const DELETED = 1;

    public static function instantiate($row)
    {
        return \Yii::createObject(static::className());
    }

    public function scenarios()
    {
        $scenarios = array_merge(parent::scenarios(),
            [
                self::SCENARIO_INSERT => $this->attributesForSave(self::SCENARIO_INSERT),
                self::SCENARIO_UPDATE => $this->attributesForSave(self::SCENARIO_UPDATE),
                self::FILTER_SCENARIO => $this->filterAttributes(),
                self::FILTER_ONE_SCENARIO => $this->filterOneAttributes()
            ]);
        return $scenarios;

    }

    public function attributesForSave($scenario)
    {

        if (static::tableName() == "{{%active_record}}") return [];

        $attributes = $this->attributes();

        foreach ($attributes as $index => $attr) {
            $primary = $this->primaryKey();
            if (is_array($primary)) {
                foreach ($primary as $pk) {
                    if ($attr == $pk) {
                        unset($attributes[$index]);
                    }
                }
            } else {
                if ($attr == $primary) {
                    unset($attributes[$index]);
                }
            }
        }

        return $attributes;
    }

    public function filterAttributes()
    {
        return [];
    }

    public function filterOneAttributes()
    {
        return [];
    }


    /**
     * @return ActiveQuery
     */
    public static function find()
    {
        return (new ActiveQuery(get_called_class()))->notDeleted();
    }

    public static function findWithDeleted()
    {
        return (new ActiveQuery(get_called_class()));
    }

    public static function findUnScoped()
    {
        return (new ActiveQuery(get_called_class()))->notDeleted();
    }

    // Сохраняет предыдущие значения аттрибутов, перед записью в базу
    public function beforeSave($insert)
    {
        if (in_array("info", $this->attributes()) && !is_array($this->info)) {
            $this->info = json_decode($this->info, true);
        }

        return parent::beforeSave($insert);
    }

    public function setInfo($name, $value)
    {
        $jInfo = $this->infoJson;
        if (!$jInfo) {
            $jInfo = array();
        }
        $jInfo[$name] = $value;
        $this->info = $jInfo;
    }

    public function updateInfo()
    {
        $this->save();
    }

    public function __get($name)
    {
        if (substr($name, strlen($name) - 4, 4) == 'Json') {
            $name = substr($name, 0, strlen($name) - 4);
            $attr = parent::__get($name);
            return is_array($attr) ? $attr : (json_decode($attr, true) ? json_decode($attr, true) : []);
        }

        if (substr($name, strlen($name) - 6, 6) == 'ByLang') {
            $name = substr($name, 0, strlen($name) - 6);
            $attr = parent::__get($name);
            $data = json_decode($attr, true);
            if (is_array($data)) {
                return (isset($data[\Yii::$app->language]) ? ($data[\Yii::$app->language]) : $data[key($data)]);
            }
            return parent::__get($name);
        }

        return parent::__get($name);
    }

    /**
     * @param ActiveQuery $query
     * @return mixed|void
     */
    public function applyFilter(&$query)
    {
        foreach ($this->attributes as $key => $value) {
            if ($value != null) {
                $query->andWhere([
                    $key => $value
                ]);
            }
        }
    }

    public function applyFilterOne(&$query)
    {

    }

    public function isExpandAttribute($attr)
    {
        $expand = explode(",", \Yii::$app->request->get("expand"));
        if (in_array($attr, $expand)) {
            return true;
        }
        return false;
    }

    public function getHash()
    {
        return md5($this->id.\Yii::$app->params['secret_word']);
    }

    /**
     * Загружает весь список (использовать для небольших таблиц)
     */
    public static function cache($reset = false)
    {
        if (!\Yii::$app->has('_preloaded_'.static::tableName()) OR $reset) {
            \Yii::$app->set("_preloaded_".static::tableName(), function() {
                return static::find()->indexBy('id')->all();
            });
        }
        return \Yii::$app->get("_preloaded_".static::tableName());
    }

}

