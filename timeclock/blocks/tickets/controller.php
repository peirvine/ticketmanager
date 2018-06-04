<?php 
namespace Concrete\Package\Timeclock\Block\Tickets;
use Concrete\Core\Block\BlockController;
use Loader;
use \Imagick;
use User;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends BlockController
{
    protected $btTable = 'btTickets';
    

    public function getBlockTypeDescription()
    {
        return t("This is the ticket submission block");
    }

    public function getBlockTypeName()
    {
        return t("Ticket User Interface");
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
		//Viewing Tickets
		$look = "SELECT * FROM a_tickets WHERE uID = ". $u->getUserID() ."";
		$tickets = $this->_db->query($look);
		$this->set('tickets',$tickets);
		//End Viewing Tickets
		
		//Submitting Tickets
		if (isset($_POST['tickets'])){
            $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
            $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
            
			$log = "INSERT INTO a_tickets (`uID`, `hoursdifference`, `description`, `datesubmitted`,`handled`,`accepted`) VALUES (". $u->getUserID() .", ". str_replace($search, $replace, $_POST['hoursdifference']) .",'". str_replace($search, $replace, $_POST['description1']) ."', NOW(),0,0)";
			$this->_db->query($log);
			//header("Location: https://mngofirst.org/clock/");
			//exit;
            header("Refresh:0");
		}
		//End Submitting Tickets
		
    }

    public function delete()
    {

    }

    public function save($args)
    {

    }
    

}