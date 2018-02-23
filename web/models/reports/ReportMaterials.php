<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 16.01.2018
 * Time: 19:31
 */

namespace app\models\reports;


use app\models\results\MaterialResults;
use app\models\results\TaskResults;

class ReportMaterials extends ReportAbstract
{
    public function getLists()
    {
        return parent::getLists();
    }

    public function getData()
    {

        $start = (new \DateTime($this->filter->start))->format('d.m.Y H:i:s');
        $end = date('d.m.Y H:i:s', strtotime($this->filter->end) + 86399);

        $materialResults = MaterialResults::find()
            ->byOrganization()
            ->with([
                "material",
                "user"
            ])
            ->andWhere("material_results.process IS NOT NULL")
            ->andWhere([
                "between", "material_results.ts", $start, $end
            ])
            ->orderBy("material_results.ts DESC");

        $this->applyCustom($materialResults);

        return $materialResults->all();

    }
}