<?php

namespace common\traits;


/**
 * Трейт для работы с номерами телефона.
 * При выводе добавлят +7 и скобки
 *
 * @package common\traits
 */
trait PhoneTrait
{
    public function getFormattedPhone() {
        return $this->formatted();
    }

    /**
     * Возвращаем форматированный вид номера телефона
     *
     * @param string $attribute
     * @return string
     */
    public function formatted($attribute='phone') {
        if (!$phone = $this->$attribute) {
            return '';
        }

        // Clear phone number. Remove all non-digit characters
        $phone = preg_replace('/\D+/', '', $phone);

        // В базе пустые телефоны могут быть записаны как 87
        if (strlen($phone) !== 11) {
            return '';
        }

        return '+7 ('.substr($phone, 1, 3).') '
            .substr($phone, 4, 3). '-'
            .substr($phone, 7, 2). '-'
            .substr($phone, 9);
    }
}