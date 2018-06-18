<?php
namespace Concrete\Package\Timeclock\Controller\SinglePage\Dashboard\TimeClock;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\File\File;
use Loader;
use \Imagick;

class Clockmanager extends DashboardPageController {

	
	public function view() {
		$this->_db = Loader::db();	
        $this->set('show','list');
        
		if (isset($_GET['delete'])){
			$v = array(intval($_GET['delete']));
			$q = 'DELETE FROM `competition1` WHERE event_id = ?';
			$r = $this->_db->query($q, $v);
			
			header("Location: /dashboard/time_clock/clock_manager/");
			exit;
		}

		$query = 'SELECT (UNIX_TIMESTAMP(`clockout`) - UNIX_TIMESTAMP(`clockin`))/3600 as logged, timeclock_clock.clockin as clockin, timeclock_clock.clockout as clockout, timeclock_clock.uID as uID, timeclock_clock.description as description, UserSearchIndexAttributes.ak_full_name as ak_full_name FROM `timeclock_clock` LEFT JOIN (UserSearchIndexAttributes) ON (UserSearchIndexAttributes.uID = timeclock_clock.uID) ORDER BY `hoursID` DESC'; 
		$r = $this->_db->query($query);	
		$this->set('r', $r);	
		
		
		$query2 = "SELECT SUM((UNIX_TIMESTAMP(`clockout`) - UNIX_TIMESTAMP(`clockin`))/3600) as `hours1`, timeclock_clock.uID as uID, UserSearchIndexAttributes.ak_full_name as fullname FROM `timeclock_clock` LEFT JOIN (UserSearchIndexAttributes) ON (UserSearchIndexAttributes.uID = timeclock_clock.uID) WHERE `clockout` IS NOT NULL GROUP BY timeclock_clock.`uID` ";
		$hours = $this->_db->query($query2);	
		$this->set('hours', $hours);
		
		$query3 = "SELECT SUM((UNIX_TIMESTAMP(`clockout`) - UNIX_TIMESTAMP(`clockin`))/3600) as `total`FROM `timeclock_clock` WHERE `clockout` IS NOT NULL";
		$total = $this->_db->query($query3);	
		$this->set('total', $total);
		
		$query4 = "SELECT * FROM `timeclock_clock` WHERE `clockout` IS NULL GROUP BY uID, hoursID";
		$status = $this->_db->query($query4);	
		$this->set('status', $status);
	}
	
	public function edit($id){

		$this->_db = Loader::db();
		
		$this->set('editID', $id);
		
		if(!isset($_POST['edit'])){
			
			$v = array(intval($id));
			$query = 'SELECT * FROM timeclock_clock WHERE hoursID = ? '; 
			$r = $this->_db->query($query, $v);
			
			if ($r->rowCount() != 1){
				$this->error->add('No Such Event ID');
				return;
				
			}

			$this->set('show','form');
			$data = $r->fetch();
			
			$this->set('data', $data);
		} else{
		

		
			

			
			//Process error
			if(isset($this->error) && $this->error->has()){		
				$this->set('show','form');
			} else {
				$allowed_fields = array('clockin','clockout','description');

				$data = array();
				foreach ($_POST as $key=>$value){
					if (in_array($key, $allowed_fields)){
						$data[$key] = $value;
					}
				}

				
				$q = 'UPDATE `timeclock_clock` SET ';
				foreach($data as $col=>$val){
					$q .= '`'.$col.'`=?,';
					$dropins[] = $val;
				}
				$q = substr($q, 0, -1);
				$q .= ' WHERE `hoursID`=?';
				$dropins[] = $_POST['id'];
				
				//$message .= '$query = ' . $q ."\n";

				
				$this->_db->Execute($q, $dropins);
				
				$updatepdf = false;
				//$page = new Imagick();
				//$page->setResolution(144,144);
			
				$message = 'Clock Entry Updated';

				
				
				$this->set('message', $message);
				$this->view();
			}
		}
	}
	
	public function download1($period_id){
		require_once '/usr/share/php/Spreadsheet/Excel/Writer.php';
	}
	
	public function download2($period_id){
		$this->_db = Loader::db();			
		$query = "SELECT * FROM `timeclock_clock` LEFT JOIN (UserSearchIndexAttributes) ON (UserSearchIndexAttributes.uID = timeclock_clock.uID)";
		$r = $this->_db->query($query, array($period_id));
		$data = $r->fetchAll();
		
		require_once "PHPExcel.php";
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Webmaster")
							 ->setLastModifiedBy("Webmaster")
							 ->setTitle("Time Clock Report")
							 ->setSubject("Time Clock Report")
							 ->setDescription("A list of all the clock entries")
							 ->setKeywords("")
							 ->setCategory("");
							 
		$i = 1;
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'. $i, 'User ID')
				->setCellValue('B'. $i, 'User Name')
				->setCellValue('C'. $i, 'Clock in time')
				->setCellValue('D'. $i, 'Clock out time')
				->setCellValue('E'. $i, 'Total clocked time')
				->setCellValue('F'. $i, 'Description of Hours worked')			
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
			} elseif (substr($row['value'],0,6) == 'select'){ //Do Nothing
			} elseif (substr($row['value'],0,6) == 'yes/no'){ //Do Nothing
			} else {
				$good_data = "INVALID DATA TYPE. PLEASE CONTACT SUPPORT!";
			}	
			$time = (strtotime($row['clockout']) - strtotime($row['clockin'])) / 3600;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'. $i, $row['uID']) // uesr's name
				->setCellValue('B'. $i, $row['ak_full_name'])
				->setCellValue('C'. $i, $row['clockin'])//uID
				->setCellValue('D'. $i, $row['clockout'])
				->setCellValue('E'. $i, $time) // mID
				->setCellValue('F'. $i, $row['description']);
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
		$objPHPExcel->getActiveSheet()->setTitle('Clock Entries');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Time_Clock_Log.xls"');
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
    
    
	public function backup(){
		$this->_db = Loader::db();
		//create table destination.table select * from source.table;
		//insert into destination.table select * from source.table

		$query1 = "INSERT INTO timeclock_clockEverything (`uID`, `clockin`, `clockout`, `description`)
                   SELECT `uID`, `clockin`, `clockout`, `description`
                   FROM timeclock_clock";
       
		$this->_db->query($query1);

		$query2 = "TRUNCATE timeclock_clock";
		$this->_db->query($query2);
		
		header("Location: /dashboard/time_clock/clock_manager/");
		exit;
	}
    
    
	public function clockout($userID){
		$this->_db = Loader::db();
		$query = "UPDATE `timeclock_clock` SET clockout = NOW(), description = 'Forced clock out by admin' WHERE `clockout` IS NULL AND uID = ". $userID ."";
		$this->_db->query($query);
		header("Location: /dashboard/time_clock/clock_manager/");
		exit;
	}
}
