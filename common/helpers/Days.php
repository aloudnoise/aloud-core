<?php

namespace common\helpers;


/**
 * Класс дней недели, просто чтобы в коде было понятнее и чуточку красивее
 *
 * @package common\helpers
 */
class Days
{
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 7;

    /**
     * Список всех дней недели
     *
     * @return array
     */
    public static function getDays()
    {
        return [
            self::MONDAY => \Yii::t('main', 'Понедельник'),
            self::TUESDAY => \Yii::t('main', 'Вторник'),
            self::WEDNESDAY => \Yii::t('main', 'Среда'),
            self::THURSDAY => \Yii::t('main', 'Четверг'),
            self::FRIDAY => \Yii::t('main', 'Пятница'),
            self::SATURDAY => \Yii::t('main', 'Суббота'),
            self::SUNDAY => \Yii::t('main', 'Воскресенье'),
        ];
    }

    /**
     * Заголовое дня
     *
     * @param int $day
     * @return mixed
     */
    public static function getDay(int $day) {
        return self::getDays() [$day];
    }
}