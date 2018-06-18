<?php       

namespace Concrete\Package\Timeclock;
use Package;
use SinglePage;
use BlockType;
use Loader;
//use PageTheme;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package
{

	protected $pkgHandle = 'timeclock';
	protected $appVersionRequired = '5.7.1.0';
	protected $pkgVersion = '0.8.0.0';
	
	
	
	public function getPackageDescription()
	{
		return t("Time Clock and Ticket Management system. View, edit, delete clock entries and approve or deny ticket submissions. Export database to excel spreadsheet and create a back up of the database. NOTE: Ticket system should be used first for logs.");
	}

	public function getPackageName()
	{
		return t("Time Clock");
	}
	
	public function install()
	{
		$pkg = parent::install();
		// Add clockmangaer page: allows admins to view clock entries 
		SinglePage::add('/dashboard/time_clock/clock_manager/', $pkg);
        
        // Add the ticket manager page: allows admins to adjust clock entries 
        // when users 
        SinglePage::add('/dashboard/time_clock/ticket_manager/', $pkg);
        
        // Add edit user page so admins can update users' names on the clock page
        SinglePage::add('/dashboard/time_clock/edit_users/', $pkg);
        
        // Add a historical data page
        SinglePage::add('/dashboard/time_clock/historical_data/', $pkg);
        
        // Add blocks that are necessary for the users to use the package
        
        // User clock interface
        BlockType::installBlockTypeFromPackage('clock', $pkg);
        
        // User ticket interface
        BlockType::installBlockTypeFromPackage('tickets', $pkg);  
        
        $this->_db = Loader::db();	
        $query = 'ALTER TABLE `UserSearchIndexAttributes` ADD `ak_full_name` text'; 
		$this->_db->query($query);	

	}
    
	function upgrade() {
        parent::upgrade();
   	}
    
    public function uninstall() {
        parent::uninstall();
    }
}
?>