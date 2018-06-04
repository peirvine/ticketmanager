<?php
namespace Concrete\Package\Timeclock\Controller\SinglePage\Dashboard;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\File\File;
use Loader;
use \Imagick;

class editusers extends DashboardPageController {

	
	public function view() {
		$this->_db = Loader::db();	

		
	
		
		$query = 'SELECT * FROM `UserSearchIndexAttributes`'; 
		$r = $this->_db->query($query);	
		$this->set('r', $r);	
		$this->set('show','list');
		
	}
	
	public function edit($id){

		$this->_db = Loader::db();
		
		$this->set('editID', $id);
		
		if(!isset($_POST['edit'])){
			
			$v = array(intval($id));
			$query = 'SELECT * FROM UserSearchIndexAttributes WHERE uID = ? '; 
			$r = $this->_db->query($query, $v);
			
			if ($r->rowCount() != 1){
				$this->error->add('No Such Meeting ID');
				return;
				
			}

			$this->set('show','form');
			$data = $r->fetch();
			
			$this->set('data', $data);
		} else{
		

		
			if(!isset($_POST['uID']) || $_POST['ak_full_name'] == '')
				$this->error->add('Name not set.');
			//if(!isset($_POST['family_email']) || $_POST['family_email'] == '')
			//	$this->error->add('Meeting email not set.');
			//if(!isset($_POST['family_website']) || $_POST['family_website'] == '')
			//	$this->error->add('Meeting website not set.');
			if(!isset($_POST['id']) || $_POST['id'] == '')
				$this->error->add('ID value is incorrect');
			/*
			if(!isset($_FILES['family_avatar']['tmp_name']))
				$this->error->add('Meeting picture not provided.');
			if(!isset($_FILES['family_pdf']['tmp_name']))
				$this->error->add('Meeting PDF not provided.');
			*/
		
			$message = '';

			
			//Process error
			if(isset($this->error) && $this->error->has()){		
				$this->set('show','form');
			} else {
				$allowed_fields = array('ak_full_name');

				$data = array();
				foreach ($_POST as $key=>$value){
					if (in_array($key, $allowed_fields)){
						$data[$key] = $value;
					}
				}

				
				$q = 'UPDATE `UserSearchIndexAttributes` SET ';
				foreach($data as $col=>$val){
					$q .= '`'.$col.'`=?,';
					$dropins[] = $val;
				}
				$q = substr($q, 0, -1);
				$q .= ' WHERE `uID`=?';
				$dropins[] = $_POST['id'];
				
				//$message .= '$query = ' . $q ."\n";

				
				$this->_db->Execute($q, $dropins);
				
				$updatepdf = false;
				//$page = new Imagick();
				//$page->setResolution(144,144);
			
				$message = 'User Edited Successfully.';
				
				
				$this->set('message', $message);
				$this->view();
			}
		}
	}
}
