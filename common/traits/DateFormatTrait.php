<?php
namespace common\traits;


trait DateFormatTrait
{

    public function getDateTime($attribute) {
        return new \DateTime($this->$attribute);
    }

    public function getByFormat($attribute, $format) {
        $d = new \DateTime($this->$attribute);
        return $d->format($format);
    }

}