<?php
namespace Concrete\Package\Timeclock\Controller\SinglePage\Dashboard;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\File\File;
use Loader;
use \Imagick;

class ticketmanager extends DashboardPageController {

	
	public function view() {
		$this->_db = Loader::db();	

		if (isset($_GET['reject'])){
			$v = array(intval($_GET['reject']));
			$q = 'UPDATE a_tickets SET handled = 1, accepted = 1 WHERE ticket_id = ?';
			//$q = 'DELETE FROM `a_tickets` WHERE ticket_id = ?';
			$r = $this->_db->query($q, $v);
			
			header("Location: /dashboard/ticket_manager/");
			exit;
		}
		if (isset($_GET['approve'])){
			$v = array(intval($_GET['approve']));
			$asd = $_GET['difference'] * 60;
			$a = 'INSERT INTO a_clock (uID,clockin,clockout,description) VALUES('.$_GET['uid'].',NOW(), DATE_ADD(now(), INTERVAL '.$asd.' MINUTE) ,"Ticket: '. $_GET['description'].'")';
			$b = $this->_db->query($a, $v);
			
			
			$q = 'UPDATE a_tickets SET handled = 1, accepted = 2 WHERE ticket_id = ?';
			$r = $this->_db->query($q, $v);
			
			
			
			header("Location: /dashboard/ticket_manager/");
			exit;
		}
	
		
		$query = 'SELECT * FROM `a_tickets` LEFT JOIN (UserSearchIndexAttributes) ON (UserSearchIndexAttributes.uID = a_tickets.uID) ORDER BY a_tickets.ticket_id DESC'; 
		$r = $this->_db->query($query);	
		$this->set('r', $r);	
		$this->set('show','list');	
	}
	
	public function download1($period_id){
		require_once '/usr/share/php/Spreadsheet/Excel/Writer.php';
	}
	
	public function download2($period_id){
		$this->_db = Loader::db();			
		$query = "SELECT * FROM `a_tickets` LEFT JOIN (UserSearchIndexAttributes) ON (UserSearchIndexAttributes.uID = a_tickets.uID)";
		$r = $this->_db->query($query, array($period_id));
		$data = $r->fetchAll();
		
		require_once "PHPExcel.php";
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Webmaster")
							 ->setLastModifiedBy("Webmaster")
							 ->setTitle("Ticket Report")
							 ->setSubject("Ticket Report")
							 ->setDescription("A list of all the tickets submitted")
							 ->setKeywords("")
							 ->setCategory("");
							 
		$i = 1;
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'. $i, 'Ticket ID')
				->setCellValue('B'. $i, 'User')
				->setCellValue('C'. $i, 'User ID')
				->setCellValue('D'. $i, 'Hour Difference Requested')
				->setCellValue('E'. $i, 'Description of Hours worked')
				->setCellValue('F'. $i, 'Date Submitted')
				->setCellValue('G'. $i, 'Accepted')			
				;
				
				
				$i = 2;
		foreach ($data as $row){
			$good_data = unserialize($row['data']);
			if ($good_data == ''){			 //Do Nothing;
			} elseif ($row['value'] == '#'){ //Do nothing;
			} elseif ($row['value'] == '$'){ //Add Dollar;
				$good_data = '$'. $good_data;
			} elseif ($row['value'] == '%'){
				
			} elseif (substr($row['value'],0,7) == '$ Range'){
				$array_data = $good_data;
				$good_data = 'Low: $'. $good_data['low'] ."\n".'
					  High: $'. $good_data['high'] .'';
			} elseif (substr($row['value'],0,4) == 'memo' || $row['value'] == 'text'){
				$good_data = strip_tags($good_data);
			} elseif (substr($row['value'],0,5) == 'check'){
				$options = explode(";", $row['Units']);
				//print_r($good_data);
				//print_r($options);
				//exit;
				$string = '';
				$is_array = true;
				foreach ($options as $option){
					if(is_array($good_data)){
						$array_data = $good_data;
						if (array_key_exists($option,$good_data)){
							$string .= $option ."\n";
						} else {
							//$string = 'um..';
						}
					}else{
						$is_array = false;
						//print_r($good_data);
						//exit;
					}
				}
				$good_data = $string;
			} elseif (substr($row['value'],0,6) == 'select') { // Do Nothing
			} elseif (substr($row['value'],0,6) == 'yes/no') { // Do Nothing
			} else {
				$good_data = "INVALID DATA TYPE. PLEASE CONTACT SUPPORT!";
			}	
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'. $i, $row['ticket_id'])
				->setCellValue('B'. $i, $row['ak_full_name']) // uesr's name
				->setCellValue('C'. $i, $row['uID']) // uID
				->setCellValue('D'. $i, $row['hoursdifference'])
				->setCellValue('E'. $i, $row['description']) // description
				->setCellValue('F'. $i, $row['datesubmitted']) // submitted
				->setCellValue('G'. $i, $row['accepted']);
				if (substr($row['value'], 0,5) == "check" && $is_array){
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$i, 'SEE_EXTRA_COLUMNS:'. $good_data);
					$options = explode(";", $row['Units']);
					$z = 0;
					foreach ($options as $option){
						if (array_key_exists($option,$array_data)){
							$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr(73+$z).$i, $option);
						}
						$z++;
					}
					

				}	
				
			$i++;
		}

		
				// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Tickets');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Ticket_Log.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;

	}
}
