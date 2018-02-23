<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 07.05.2017
 * Time: 19:14
 */

namespace app\models;


use app\traits\BackboneRequestTrait;

class Dics extends \common\models\Dics
{

    public static $classes = [
        'test_criteria_templates' => 'app\models\dics\CriteriaTemplates',
        'pupil_custom_fields' => 'app\models\dics\PupilFields',
        'DicQuestionThemes' => 'app\models\dics\Themes'
    ];

    use BackboneRequestTrait;

}