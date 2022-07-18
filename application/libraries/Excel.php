<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Excel
{

  private $excel;

  public function __construct()
  {
    // initialise the reference to the codeigniter instance
    require_once APPPATH . 'third_party/PHPExcel.php';
    $this->excel = new PHPExcel();
  }

  public function load($path)
  {
    $objReader = PHPExcel_IOFactory::createReader('Excel5');
    $this->excel = $objReader->load($path);
  }

  public function save($path)
  {
    // Write out as the new file
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
    $objWriter->save($path);
  }

  public function stream($filename, $data = null)
  {
    if ($data != null) {
      $col = 'A';
      foreach ($data[0] as $key => $val) {
        $objRichText = new PHPExcel_RichText();
        $objPayable = $objRichText->createTextRun(str_replace("_", " ", $key));
        $objPayable->getFont()->setBold(true);
        $objPayable->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));
        $this->excel->getActiveSheet()->getCell($col . '1')->setValue($objRichText);
        //$objPHPExcel->getActiveSheet()->setCellValue($col.'1' , str_replace("_"," ",$key));
        $col++;
      }
      $rowNumber = 2; //start in cell 1
      foreach ($data as $row) {
        $col = 'A'; // start at column A
        foreach ($row as $cell) {
          $this->excel->getActiveSheet()->setCellValue($col . $rowNumber, strip_tags($cell));
          $col++;
        }
        $rowNumber++;
      }
    }
    header('Content-type: application/ms-excel');
    header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
    header("Cache-control: private");
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
    //$objWriter->save('php://output');
    $objWriter->save("assets/export/$filename");
    header("location: " . base_url() . "assets/export/$filename");
    unlink(base_url() . "assets/export/$filename");
  }

  public function streamMultipleSheet($filename, $reports = [])
  {
    foreach ($reports as $x => $report) {
      $data = $report['data'];
      // Add new sheet
      $objWorkSheet = $this->excel->createSheet($x);
      // Rename sheet
      $objWorkSheet->setTitle($report['title']);

      if ($data != null) {
        $col = 'A';
        foreach ($data[0] as $key => $val) {
          $objRichText = new PHPExcel_RichText();
          $objPayable = $objRichText->createTextRun(strtoupper(str_replace("_", " ", $key)));
          $objPayable->getFont()->setBold(true);
          $objPayable->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_BLACK));
          $objWorkSheet->getCell($col . '1')->setValue($objRichText);
          //$objPHPExcel->getActiveSheet()->setCellValue($col.'1' , str_replace("_"," ",$key));
          $col++;
        }
        $rowNumber = 2; //start in cell 1
        foreach ($data as $row) {
          $col = 'A'; // start at column A
          foreach ($row as $cell) {
            $objWorkSheet->setCellValue($col . $rowNumber, strip_tags($cell));
            $col++;
          }
          $rowNumber++;
        }
      }

      $this->excel->setActiveSheetIndex(0);
    }

    header('Content-type: application/ms-excel');
    header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
    header("Cache-control: private");
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
    //$objWriter->save('php://output');
    $objWriter->save("assets/export/$filename");
    header("location: " . base_url() . "assets/export/$filename");
    unlink(base_url() . "assets/export/$filename");
  }

  public function  __call($name, $arguments)
  {
    // make sure our child object has this method
    if (method_exists($this->excel, $name)) {
      // forward the call to our child object
      return call_user_func_array(array($this->excel, $name), $arguments);
    }
    return null;
  }
}
