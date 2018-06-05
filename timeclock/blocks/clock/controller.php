<?php 
/**
 *
 * @package timeclock
 * @category blocks
 * @author Peter Irvine <peter@peirvine.com>
 * @copyright  Copyright (c) Peter Irvine
 *
 */

namespace Concrete\Package\Timeclock\Block\Clock;
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
        return t("Clock User Interface");
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
        if($u->isLoggedIn()) {
            //Queries and Code for clocking time
            if (isset($_POST['clockin'])) {
                $log = "INSERT INTO timeclock_clock (uID, clockin) VALUES (". $u->getUserID() .", NOW())";
                $this->_db->query($log);
                $this->set('blah','clockout');
            } else if (isset($_POST['clockout'])) {
                $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
                $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

                $out = "UPDATE `timeclock_clock` SET `description` = '". str_replace($search, $replace, $_POST['description']) ."', `clockout`=NOW() WHERE `clockout` IS NULL AND `uID`='". $u->getUserID() ."'";
                $this->_db->query($out);
                $this->set('blah','clockin');
            }
            
            $asdf = "SELECT * FROM `timeclock_clock` WHERE `uID`='". $u->getUserID() ."' AND `clockout` IS NULL";
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
            $query = 'SELECT (UNIX_TIMESTAMP(`clockout`) - UNIX_TIMESTAMP(`clockin`))/3600 as logged, timeclock_clock.clockin as clockin, timeclock_clock.clockout as clockout, timeclock_clock.uID as uID, timeclock_clock.description as description, UserSearchIndexAttributes.ak_full_name as ak_full_name FROM `timeclock_clock` LEFT JOIN (UserSearchIndexAttributes) ON (UserSearchIndexAttributes.uID = timeclock_clock.uID) ORDER BY `hoursID` DESC LIMIT 15'; 
            $r = $this->_db->query($query);	
            $this->set('r', $r);	
            $this->set('show','list');
            
            $query2 = "SELECT SUM((UNIX_TIMESTAMP(`clockout`) - UNIX_TIMESTAMP(`clockin`))/3600) as `hours1`, timeclock_clock.uID as uID, UserSearchIndexAttributes.ak_full_name as fullname FROM `timeclock_clock` LEFT JOIN (UserSearchIndexAttributes) ON (UserSearchIndexAttributes.uID = timeclock_clock.uID) WHERE `clockout` IS NOT NULL GROUP BY timeclock_clock.`uID` ";
            $hours = $this->_db->query($query2);	
            $this->set('hours', $hours);
            
            $query3 = "SELECT SUM((UNIX_TIMESTAMP(`clockout`) - UNIX_TIMESTAMP(`clockin`))/3600) as `total`FROM `timeclock_clock` WHERE `clockout` IS NOT NULL";
            $total = $this->_db->query($query3);	
            $this->set('total', $total);
            
            $query4 = "SELECT * FROM `timeclock_clock` WHERE `clockout` = NULL GROUP BY uID, hoursID";
            $status = $this->_db->query($query4);	
            $this->set('status', $status);
            //end queires code for this section
		}
    }

    public function delete()
    {

    }

    public function save($args)
    {

    }
    

}