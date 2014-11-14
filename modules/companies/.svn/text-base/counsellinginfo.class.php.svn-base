<?php /* COUNSELLING INFO $Id: companies.class.php,v 1.9 2004/01/29 06:30:43 ajdonnison Exp $ */
/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision: 1.9 $
*/

require_once( $AppUI->getSystemClass ('dp' ) );

/**
 *	CounsellingInfo Class
 *	@todo Move the 'address' fields to a generic table
 */
class CCounsellingInfo extends CDpObject {

var  $counselling_id = NULL;
var  $counselling_client_id = NULL;
var  $counselling_entry_date = NULL;
var  $counselling_dascop_code = NULL;
var  $counselling_referral_source = NULL;
var  $counselling_birth_location = NULL;
var  $counselling_mothers_yob = NULL;
var  $counselling_date_mothers_status_known = NULL;
var  $counselling_mother_antenatal = NULL;
var  $counselling_mother_pmtct = NULL;
var  $counselling_mother_pmtct_description = NULL;
var  $counselling_place_birth = NULL;
var  $counselling_mode_birth = NULL;
var  $counselling_gestation_period = NULL;
var  $counselling_birth_weight = NULL;
var  $counselling_notes = NULL;
var  $counselling_custom = NULL;
	



	function CCounsellingInfo() {
		$this->CDpObject( 'counselling_info', 'counselling_id' );
	}

// overload check
	function check() {
		/*if ($this->counselling_id === NULL) {
			return 'counselling id is NULL';
		}
		$this->counselling_id = intval( $this->counselling_id );*/

		return NULL; // object is ok
	}

// overload canDelete
	function canDelete( &$msg, $oid=null ) 
	{
		
	}
	function getContacts($type = NULL)
	{
		$contacts = NULL;
		$q = new DBQuery;
		
		if (isset($this->company_id))
		{
			$q->addTable('company_contacts');
			$q->addQuery('company_contacts_contact_id');
			$q->addWhere("company_contacts_company_id = $this->company_id");
			if ($type)
			   $q->addWhere("company_contacts_contact_type = $type");
			   
			$contacts = $q->loadColumn();
		}
		//if (count($contacts)==1)
		   //$contacts = $contacts[0];
		   
		return $contacts;

	}
	function getUrl($urlType='view', $companyType = NULL)
	{
		if ($companyType == NULL) $companyType = $this->company_type;
		
		
		$modules = dPgetSysVal('CompanyModules');
		$unit = $modules[$companyType];
		$url_array = array(
		"view" => "./index.php?m=counsellinginfo&a=view&company_id=$this->company_id",
		"add" => "./index.php?m=counsellinginfo&a=addedit&company_type=$companyType",
		"edit"=> "./index.php?m=counsellinginfo&a=addedit&company_id=$this->company_id"
		);
		return $url_array[$urlType];
	}
	function getDescription()
	{
		static $types; 
		if (!isset($types)) 
		{
			$types = dPgetSysVal('CompanyType');
		}
		$desc = $types[$this->company_type];
		return $desc;
	}
	function getCount($type = NULL)
	{
				if (!empty($type))
				{
					$sql = "SELECT COUNT(*) FROM companies WHERE company_type IS NOT NULL AND company_type = $type";
				}
				else
				{
					$sql = "SELECT COUNT(*)  FROM companies WHERE company_type IS NOT NULL";
				}
		$count = db_loadResult($sql);
		return $count;
  }
}
?>
