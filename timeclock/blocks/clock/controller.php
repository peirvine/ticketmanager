<?php 
namespace Application\Block\Clock;
use Concrete\Core\Block\BlockController;
use Loader;
use \Imagick;
use User;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends BlockController
{
    protected $btTable = 'btClock';
    

    public function getBlockTypeDescription()
    {
        return t("This is the user side to the clock package");
    }

    public function getBlockTypeName()
    {
        return t("Actual Clock");
    }

    public function add()
    {
    }

    public function edit()
    {
    }

    public function view(){
		$this->_db = Loader::db();	
		$u = new User();
		//Queries and Code for clocking time
		if (isset($_POST['clockin'])){
			$log = "INSERT INTO a_clock (uID, clockin) VALUES (". $u->getUserID() .", NOW())";
			$this->_db->query($log);
			$this->set('blah','clockout');
		}else if (isset($_POST['clockout'])){
			$out = "UPDATE `a_clock` SET `description` = '". mysql_escape_string($_POST['description']) ."', `clockout`=NOW() WHERE `clockout`='0000-00-00 00:00:00' AND `uID`='". $u->getUserID() ."'";
			$this->_db->query($out);
			$this->set('blah','clockin');
		}
		
		$asdf = "SELECT * FROM `a_clock` WHERE `uID`='". $u->getUserID() ."' AND `clockout` = '0000-00-00 00:00:00'";
		$result = $this->_db->query($asdf);
		$count = 0;
		$this->set('blah', 'clockin');
		while($result->fetchRow()) {
			$count++;
		}
		if ($count >= 1){
			$this->set('blah', 'clockout');
		}
		
		$this->set('count',$count);
		
		
		
		
		//End queries and code for this section
		
		$this->set('status','clockin');
		//The following queries and code are for the table of users and for the total time at the bottom of said table
		$query = 'SELECT (UNIX_TIMESTAMP(`clockout`) - UNIX_TIMESTAMP(`clockin`))/3600 as logged, a_clock.clockin as clockin, a_clock.clockout as clockout, a_clock.uID as uID, a_clock.description as description, UserSearchIndexAttributes.ak_full_name as ak_full_name FROM `a_clock` LEFT JOIN (UserSearchIndexAttributes) ON (UserSearchIndexAttributes.uID = a_clock.uID) ORDER BY `hoursID` DESC LIMIT 15'; 
		$r = $this->_db->query($query);	
		$this->set('r', $r);	
		$this->set('show','list');
		
		$query2 = "SELECT SUM((UNIX_TIMESTAMP(`clockout`) - UNIX_TIMESTAMP(`clockin`))/3600) as `hours1`, a_clock.uID as uID, UserSearchIndexAttributes.ak_full_name as fullname FROM `a_clock` LEFT JOIN (UserSearchIndexAttributes) ON (UserSearchIndexAttributes.uID = a_clock.uID) WHERE `clockout` != '0000-00-00 00:00:00' GROUP BY a_clock.`uID` ";
		$hours = $this->_db->query($query2);	
		$this->set('hours', $hours);
		
		$query3 = "SELECT SUM((UNIX_TIMESTAMP(`clockout`) - UNIX_TIMESTAMP(`clockin`))/3600) as `total`FROM `a_clock` WHERE `clockout` != '0000-00-00 00:00:00'";
		$total = $this->_db->query($query3);	
		$this->set('total', $total);
		
		$query4 = "SELECT * FROM `a_clock` WHERE `clockout` = '0000-00-00 00:00:00' GROUP BY uID, hoursID";
		$status = $this->_db->query($query4);	
		$this->set('status', $status);
		//end queires code for this section
		
    }

    public function delete()
    {

    }

    public function save($args)
    {

    }
    

}