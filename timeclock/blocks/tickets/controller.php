<?php 
namespace Application\Block\Tickets;
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
        return t("Ticket form and viewer");
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
			$log = "INSERT INTO a_tickets (`uID`, `hoursdifference`, `description`, `datesubmitted`) VALUES (". $u->getUserID() .", ". mysql_escape_string($_POST['hoursdifference']) .",'". mysql_escape_string($_POST['description1']) ."', NOW())";
			$this->_db->query($log);
			header("Location: https://mngofirst.org/clock/");
			exit;
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