<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 28.04.2017
 * Time: 17:12
 */

namespace aloud_core\web\traits;


trait BackboneRequestTrait
{

    public function filterRulesForBackboneValidation()
    {

        $scenario = $this->getScenario();

        $raw = $this->rules();

        $backbone_rule_types = [
            "compare",
            "date",
            "email",
            "in",
            "length",
            "numerical",
            "match",
            "type"
        ];

        $rules = [];
        if (!empty($raw)) {
            foreach ($raw as $r) {

                if (isset($r['on']) AND $r['on'] != $scenario) continue;

                if (!in_array($r['1'], $backbone_rule_types)) continue;

                $rules[] = $r;

            }
        }

        return $rules;

    }

    public function requestAccess($type = "fetch", $attributes = [])
    {
        $m = $type."Access";
        return $this->$m($attributes);
    }
    public function getAccess($attributes = [])
    {
        return false;
    }
    public function insertAccess($attributes = [])
    {
        return false;
    }
    public function updateAccess($attributes = [])
    {
        return false;
    }
    public function deleteAccess($attributes = [])
    {
        return false;
    }
    public function pollAccess($attributes = [])
    {
        return false;
    }

    public function backboneRequest($type, $attributes = [])
    {
        if ($this->requestAccess($type, $attributes))
        {
            $m = $type."Request";
            return $this->$m($attributes);
        } else {
            $this->addError("access", \Yii::t("main","Нет доступа"));
            return false;
        }
    }
    public function getRequest($attributes = [])
    {
        return self::arrayAttributes($this->owner->findAllByAttributes($attributes));
    }

    public function insertRequest($attributes)
    {
        $this->attributes = $attributes;
        if ($this->save())
        {
            return self::arrayAttributes($this);
        } else {
            return false;
        }
    }

    public function updateRequest($attributes)
    {

    }

    public function pollRequest($attributes)
    {

    }

    public function fieldTypes()
    {
        return [];
    }

    /**
     * Возвращает аттрибуты модели в виде массива
     *
     * @param mixed $models - модель
     * @param array $relations - если нужно обработать связи
     * @param array $fields
     * @param bool $allowEmpty
     * @return array
     */
    public static function arrayAttributes($models, $relations = [], $fields = [], $allowEmpty = false)
    {
        if (is_array($models)) {
            $result = [];
            foreach ($models as $k=>$m) {

                if (empty($fields)) {
                    $fields = $m->fields();
                }

                $attr = [];
                foreach ($fields as $f) {
                    if (method_exists($m, $f)) {
                        $attr[$f] = $m->$f();
                    } else $attr[$f] = $m->$f;
                }

                $types = [];
                if (method_exists($m, 'fieldTypes')) {
                    $types = $m->fieldTypes();
                }

                foreach ($attr as $name=>$value) {
                    if (!empty($value) || $allowEmpty) {
                        if ($types[$name]) {
                            if ($types[$name] == "string") {
                                $result[$k][$name] = $value;
                            }
                        } else {
                            $result[$k][$name] = is_numeric($value) ? (($value == (int)$value) ? (int)$value : (float)$value) : $value;
                        }
                    }
                }
                if (!empty($relations))
                {
                    foreach ($relations as $r=>$v) {
                        if (!is_array($v)) {
                            if (is_object($m->$v) OR is_array($m->$v)) {
                                $result[$k][$v] = self::arrayAttributes($m->$v, [], [], $allowEmpty);
                            } else {
                                $result[$k][$v] = $m->$v;
                            }
                        } else {

                            $_rels = [];
                            if (isset($v['relations'])) {
                                $_rels = $v['relations'];
                            }
                            $r_fields = [];
                            if (isset($v['fields'])) {
                                $r_fields = $v['fields'];
                            }

                            if (!isset($v['relations']) AND !isset($v['fields'])) {
                                $_rels = $v;
                            }

                            $result[$k][$r] = self::arrayAttributes($m->$r, $_rels, $r_fields, $allowEmpty);
                        }
                    }
                }
            }

        } else {

            if (empty($fields)) {
                $fields = $models->fields();
            }

            $attr = [];
            foreach ($fields as $f) {
                if (method_exists($models, $f)) {
                    $attr[$f] = $models->$f();
                } else $attr[$f] = $models->$f;
            }

            if ($models) {

                $types = [];
                if (method_exists($models, 'fieldTypes')) {
                    $types = $models->fieldTypes();
                }

                foreach ($attr as $name => $value) {
                    if (!empty($value) OR $allowEmpty) {
                        if ($types[$name]) {
                            if ($types[$name] == "string") {
                                $result[$name] = $value;
                            }
                        } else {
                            $result[$name] = is_numeric($value) ? (($value == (int)$value) ? (int)$value : (float)$value) : $value;
                        }

                        /*
                        Тут надо ещё учесть что может вызвать не модель, тогда фатал
                        $newType = $models->getTableSchema()->columns[$name]->phpType;//->type;
                        if ($newType=='decimal') {
                            $newType = 'double';
                        }

                        if ($newType === null) {
                            $newType = 'string';
                        }

                        // SetType не понимает тип decimal, в итоге все вещественные цисла становяться string
                        settype($value, $newType);
                        $result[$name] = $value;
                        */
                    }
                }

                if (!empty($relations)) {
                    foreach ($relations as $r => $v) {
                        if (!is_array($v)) {
                            if (is_object($models->$v) || is_array($models->$v)) {
                                $result[$v] = self::arrayAttributes($models->$v, [], [], $allowEmpty);
                            } else {
                                $result[$v] = $models->$v;
                            }
                        } else {

                            $_rels = [];
                            if (isset($v['relations'])) {
                                $_rels = $v['relations'];
                            }
                            $r_fields = [];
                            if (isset($v['fields'])) {
                                $r_fields = $v['fields'];
                            }

                            if (!isset($v['relations']) AND !isset($v['fields'])) {
                                $_rels = $v;
                            }

                            $result[$r] = self::arrayAttributes($models->$r, $_rels, $r_fields, $allowEmpty);
                        }
                    }
                }
            }
        }
        return $result;
    }


}