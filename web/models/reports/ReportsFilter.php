<?php

namespace app\models\reports;

use common\components\Model;
use yii\base\UnknownClassException;

class ReportsFilter extends Model
{

    public $type = 'tests';
    public $start = null;
    public $end = null;

    public $custom = [];

    public $details = null;

    public $template = null;

    public $from = null;

    public $items = [
        'period',
        'teacher',
        'theme',
        'group',
        'result'
    ];

    public $event = null;
    public $course = null;

    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type'], 'string'],
            [['start'], 'filter', 'filter' => function ($value) {
                return $value ?: date('d.m.Y', mktime(0, 0, 0, date('m'), 1, date('Y')));
            }],
            [['end'], 'filter', 'filter' => function ($value) {
                return $value ?: date('d.m.Y');
            }],
            [['start', 'end'], 'string'],
            [['details'], 'safe'],
            [['custom'], 'safe']
        ];
    }

    public function setFromAttributes()
    {
        if ($this->from->name == 'event') {
            $event = ($this->from->getModelClass())::find()->byPk($this->from->id)->byOrganization()->one();
            if ($event) {
                $this->event = $event;
                $this->items = [
                    'result'
                ];

                $this->start = (new \DateTime($event->begin_ts))->format('d.m.Y');
                $this->end = date('d.m.Y', strtotime((new \DateTime($event->end_ts))->format('d.m.Y')) + 86399);
            }
        }
    }

    private $_report = null;
    public function getReport() {
        if (!$this->_report) {

            $reports = $this->getReports();

            if (!isset($reports[$this->type]['class'])) {
                $className = 'Report' . ucfirst($this->type);
                $class = __NAMESPACE__ . '\\' . $className;
            } else {
                $class = $reports[$this->type]['class'];
            }
            if (!class_exists($class)) {
                \Yii::trace('Class does not exist in ' . $class);
                throw new UnknownClassException('Class `' . $class . '` not found.');
            }
            $this->_report = \Yii::createObject($class, [
                $this
            ]);
        }
        return $this->_report;
    }

    public function getLists()
    {
        return $this->report->getLists();
    }

    public function getData()
    {
        // Получаем данные
        /** @var IReport $report */
        return $this->report->getData();
    }

    public function getReports()
    {
        $reports = [
            'tests' => [
                'name' => 'tests',
                'label' => \Yii::t("main","Результаты тестирований")
            ],
            'tasks' => [
                'name' => 'tasks',
                'label' => \Yii::t("main","Результаты заданий")
            ],
            'materials' => [
                'name' => 'materials',
                'label' => \Yii::t("main","Просмотренные материалы")
            ],
        ];

        return $reports;
    }

}