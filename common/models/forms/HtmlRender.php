<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 13.01.2018
 * Time: 1:58
 */

namespace common\models\forms;


use app\helpers\PhpWord\HtmlOld;
use common\components\Model;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;

class HtmlRender extends Model
{

    public $html = null;

    public $type = 'word';
    public $font_size = 12;
    public $font_family = 'Times New Roman';
    public $orientation = 'portrait'; // portrait, landscape

    public $file_name = null;

    public $section_settings = [
        "marginTop" => 567,
        "marginBottom" => 567,
        "marginLeft" => 567,
        "marginRight" => 567
    ];

    public function rules()
    {
        return [
            [['html'], 'safe'],
            [['type'], 'required'],
            [['file_name'], 'required'],
            [['font_size'], 'integer'],
            [['font_family'], 'string'],
            [['orientation'], 'string']
        ];
    }

    public function render()
    {
        if (!$this->validate()) return false;
        $renders = [
            'word' => '_renderWord',
            'excel' => '_renderExcel'
        ];

        return $this->{$renders[$this->type]}();
    }

    public function _renderWord()
    {

        Settings::setTempDir(sys_get_temp_dir());

        $word = new PhpWord();
        $word->setDefaultFontName($this->font_family);
        $word->setDefaultFontSize($this->font_size);
        $section = $word->addSection(array_merge($this->section_settings, [
            'orientation' => $this->orientation
        ]));

        $html = trim(preg_replace('/\t+/', '',str_replace(array("\r", "\n", "<br />", "<br>"), "", $this->html)));
        $html = preg_replace('/(\>)\s*(\<)/m', '$1$2', $html);

        HtmlOld::addHtml($section, $html);

        header("Content-Type: application/msword" );
        header("Content-Disposition: attachment; filename=".urlencode($this->file_name).".docx" );

        $objWriter = IOFactory::createWriter($word, 'Word2007');
        $objWriter->save( "php://output" );
        \Yii::$app->end();
    }

    public function _renderExcel()
    {

        $file = tempnam(sys_get_temp_dir(), '_excel_export');
        $file = $file.".html";
        file_put_contents($file, $this->html);

        $objReader = new Html();

        $objPHPExcel = $objReader->load($file);

        $sheet = $objPHPExcel->getActiveSheet();

        $maxWidth = 300;

        $index = \PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());
        for ($i = 1; $i<= $index; $i++) {
            $colDim = $sheet->getColumnDimensionByColumn($i);
            $colWidth = $colDim->getWidth();
            if ($colWidth > $maxWidth) {
                $colDim->setAutoSize(false);
                $colDim->setWidth($maxWidth);
            } else {
                $colDim->setAutoSize(true);
            }
        }


        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');

        header("Content-Type: application/csv" );
        header("Content-Disposition: attachment; filename=".urlencode($this->file_name).".xlsx" );

        $objWriter->save( "php://output" );

        unlink($file);

    }

}