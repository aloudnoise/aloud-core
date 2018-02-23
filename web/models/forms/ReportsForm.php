<?php

namespace app\models\forms;

use app\models\dics\Themes;
use app\models\results\TestResults;
use common\components\Model;

class ReportsForm extends Model
{

    public static function exportToExcel($ids, $name = null, $type = null)
    {
        $type = !$type ? "simple" : $type;

        $ids = !is_array($ids) ? json_decode($ids, true) : $ids;
        $results = TestResults::find()
            ->with([
                "test",
                "user"
            ])
            ->andWhere(['in', 'id', $ids])
            ->orderBy("ts DESC")
            ->all();

        $objPHPExcel = new \PHPExcel();

        if ($name) {
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, 1, 7, 1);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, $name);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 1)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 1)->getFont()->setSize(20);
            $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(70);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 1)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, 1)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }


        // setting headers
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,2,"№");
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setWidth(5);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,2,"Тест");
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,2,"ФИО");
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,2,"Дата");
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,2,"Время прохождения");
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,2,"Правильных ответов");
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setAutoSize(true);

        if ($type == "extra") {
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(6, 2, 7, 2);
        }

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,2,"Результат");
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(6)->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);

        //settingStyles
        $styleArray = [
            'borders' => [
                'outline' => [
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF999999'],
                ],
            ]
        ];

        // Setting platform names
        $r = 3;
        $n = 1;
        if ($results) {
            if ($type == "simple") {
                foreach ($results as $result) {
                        /* @var $result TestResults */
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $r, $n);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $r, $result->test->name);
                        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, $r)->getFont()->setBold(true);

                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $r, $result->user->fio);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $r, date('d.m.Y H:i', $result->ts));
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $r, floor(($result->finished - $result->ts) / 60) . "м. " . (($result->finished - $result->ts) % 60) . "с. ");
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $r, $result->correct_answers . " из " . $result->test->qcount);
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $r, $result->result);
                        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $r)->getFont()->setBold(true);
                        $r++;
                        $n++;
                }
            } else {

                $themes = Themes::find()->orderBy("name")->indexBy("id")->all();

                foreach ($results as $result) {
                    /* @var $result TestResults */
                    $rowspan = count($result->infoJson['by_themes']);

                    /*
                    $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $r, 0, $r+$rowspan);
                    $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(1, $r, 1, $r+$rowspan);
                    $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(2, $r, 2, $r+$rowspan);
                    $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(3, $r, 3, $r+$rowspan);
                    $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(4, $r, 4, $r+$rowspan);
                    $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(5, $r, 5, $r+$rowspan);
                    $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(6, $r, 6, $r+$rowspan);
                    */

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $r, $n);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $r, $result->test->name);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1, $r)->getFont()->setBold(true);

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $r, $result->user->fio);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $r, date('d.m.Y H:i', $result->ts));
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $r, floor(($result->finished - $result->ts) / 60) . "м. " . (($result->finished - $result->ts) % 60) . "с. ");
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $r, $result->correct_answers . " из " . $result->test->qcount);

                    foreach ($result->infoJson['by_themes'] as $theme => $res) {
                        if (isset($themes[$theme])) {
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $r, $themes[$theme]->name);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $r, $res);
                        }
                        $r++;
                    }

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $r, "Общий результат (%)");
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6, $r)->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $r, $result->result);
                    $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(7, $r)->getFont()->setBold(true);

                    $r++;
                    $n++;
                }

            }
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"".($name ? : "отчет").".xlsx"."\"");

        $writer = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save( "php://output" );

    }

}

?>