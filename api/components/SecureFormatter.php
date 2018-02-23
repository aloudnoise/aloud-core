<?php

namespace api\components;


class SecureFormatter
{
    /**
     * Прячем часть email звездочками. admin@mail.ru => a***n@mail.ru
     * Функция не самая красивая, можно использовать и regex
     *
     * @param $mail
     * @param int $startIndex
     * @param int $endIndex
     * @return mixed
     */
    public static function email($mail, $startIndex=1, $endIndex=1) {
        $secureEmail = null;

        $emailArray = explode('@', $mail);
        $max = mb_strlen($emailArray[0]);
        $stopIndex = $max - $endIndex - 1;
        for ($i=0; $i<=$max; $i++) {
            if ($i>=$startIndex && $i<=$stopIndex) {
                $secureEmail.= '*';
            } else {
                $secureEmail .= $emailArray[0][$i];
            }
        }
        return $secureEmail.'@'.$emailArray[1];
    }

    /**
     * Прячем часть телефона звездочками. +777712345678 => +7*******5678
     *
     * @param $phone
     * @param $startIndex
     * @param $endIndex
     * @return mixed
     */
    public static function phone($phone, $startIndex=2, $endIndex=4) {
        $securePhone = null;

        $max = mb_strlen($phone);
        $stopIndex = $max - $endIndex - 1;
        for ($i=0; $i<=$max; $i++) {
            if ($i>=$startIndex && $i<=$stopIndex) {
                $securePhone.= '*';
            } else {
                $securePhone .= $phone[$i];
            }
        }
        return $securePhone;
    }
}