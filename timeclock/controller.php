<?php       

namespace Concrete\Package\Timeclock;
use Package;
use SinglePage;
use BlockType;
//use PageTheme;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package
{

	protected $pkgHandle = 'timeclock';
	protected $appVersionRequired = '5.7.1';
	protected $pkgVersion = '0.7.0';
	
	
	
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
		SinglePage::add('/dashboard/clockmanager/', $pkg);
        
        // Add the ticket manager page: allows admins to adjust clock entries 
        // when users 
        SinglePage::add('/dashboard/ticketmanager/', $pkg);
        
        // Add edit user page so admins can update users' names on the clock page
        SinglePage::add('/dashboard/editusers/', $pkg);
        
        // Add blocks that are necessary for the users to use the package
        
        // User clock interface
        BlockType::installBlockTypeFromPackage('clock', $pkg);
        
        // User ticket interface
        BlockType::installBlockTypeFromPackage('tickets', $pkg);  
	}
    
	function upgrade() {
        parent::upgrade();
   	}
}
?>