<?php
ini_set('display_errors',1);
error_reporting(E_ALL ^E_NOTICE);


// Подключаем класс для работы с excel
require_once('PHPExcel/PHPExcel.php');
// Подключаем класс для вывода данных в формате excel
require_once('PHPExcel/PHPExcel/Writer/Excel5.php');

class Module_xls {
     
   
   function __construct() {
       $this->module_write_xls();
   }
   
   /*Принимаем данные и записываем в массив*/
   function module_write_xls(){
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $total = $_POST['total'];
        $rows_table = $_POST['rows_table'];
        $mas= array($product_id,$quantity,$total);

        // Создаем объект класса PHPExcel
        $xls = new PHPExcel();
       //Открываем файл-шаблон
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $xls = $objReader->load('PHPExcel/import.xls');
        // Устанавливаем индекс активного листа
        $xls->setActiveSheetIndex(0);
        // Получаем активный лист
        $sheet = $xls->getActiveSheet();
        // Подписываем лист
        $sheet->setTitle('Импорт заказа');
        
        
        /*Создаем цыкл для массива и заполняем все оставшиеся поля*/
       for ($i = 0; $i< $rows_table; $i++){
           $index = 2 + $i;
           
          // (id_Product)
        $sheet->setCellValue('A'.$index, $mas[0][$i]);
        $sheet->getStyle('A'.$index)->getFill()->setFillType(
            PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('A'.$index)->getFill()->getStartColor()->setRGB('EEEEEE');
       
         // (Количество)
        $sheet->setCellValue('B'.$index, $mas[1][$i]);
        $sheet->getStyle('B'.$index)->getFill()->setFillType(
            PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('B'.$index)->getFill()->getStartColor()->setRGB('EEEEEE');
        
         // (Сумма)
        $sheet->setCellValue('C'.$index, $mas[2][$i]);
        $sheet->getStyle('C'.$index)->getFill()->setFillType(
            PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('C'.$index)->getFill()->getStartColor()->setRGB('EEEEEE');
        }
               
        
        /*Сохраняем данные в файл (путь/файл) и скачиваем*/
         $objWriter = new PHPExcel_Writer_Excel5($xls);
         $data = date("d.m.Y");
         $objWriter->save('otchet/import.xls');
         
         /*переименовываем файл по дате для скачивания*/
         $new_name = rename("otchet/import.xls", "otchet/import($data).xls");
         
        /*передаем с помощью GET запроса на скрипт для скачивания отчета*/
         if($new_name == true){
                echo "view/template/module/module_xls/downoload_script_otchet/downoload.php?file=../otchet/import($data).xls";
         }
         
    
}
}

$Module_xls = new Module_xls();
